const logger = require('../helpers/logger');
const DatabaseHelper = require('../helpers/databaseHelper');
const SocketHelper = require('../helpers/socketHelper');

class SocketService {
    constructor(io) {
        this.io = io;
        this.connectedUsers = new Map(); // userId -> socketId
        this.userSockets = new Map(); // socketId -> userId
        this.roomParticipants = new Map(); // roomId -> Set of socketIds
    }

    /**
     * Initialize socket connection
     */
    initialize() {
        this.io.on('connection', (socket) => {
            logger.info(`Socket connected: ${socket.id}`);

            // Handle user authentication from URL parameter
            this.handleConnectionAuthentication(socket);

            // Handle user disconnection
            socket.on('disconnect', () => {
                this.handleDisconnect(socket);
            });

            // Chat functionality
            socket.on('join_chat_room', (data) => {
                this.handleJoinChatRoom(socket, data);
            });

            socket.on('leave_chat_room', (data) => {
                this.handleLeaveChatRoom(socket, data);
            });

            socket.on('send_message', async (data) => {
                await this.handleSendMessage(socket, data);
            });

            socket.on('typing_start', (data) => {
                this.handleTypingStart(socket, data);
            });

            socket.on('typing_stop', (data) => {
                this.handleTypingStop(socket, data);
            });


        });
    }

        /**
     * Handle user authentication from URL parameter
     */
    async handleConnectionAuthentication(socket) {
        try {
            // Extract userId from socket handshake query
            const userId = socket.handshake.query.userId;

            if (!userId) {
                socket.emit('auth_error', {
                    message: 'Missing userId parameter in connection URL',
                    code: 'MISSING_USER_ID'
                });
                logger.warn(`Socket ${socket.id} connected without userId parameter`);
                return;
            }

            // Validate userId is a number
            const numericUserId = parseInt(userId);
            if (isNaN(numericUserId) || numericUserId <= 0) {
                socket.emit('auth_error', {
                    message: 'Invalid userId parameter',
                    code: 'INVALID_USER_ID'
                });
                logger.warn(`Socket ${socket.id} connected with invalid userId: ${userId}`);
                return;
            }

            // Check if user is already connected
            if (SocketHelper.isUserConnected(this.connectedUsers, numericUserId)) {
                // Disconnect existing connection
                const existingSocketId = SocketHelper.getUserSocketId(this.connectedUsers, numericUserId);
                if (existingSocketId) {
                    this.io.sockets.sockets.get(existingSocketId)?.disconnect();
                    logger.info(`Disconnected existing connection for user ${numericUserId}`);
                }
            }

            // Store user connection
            this.connectedUsers.set(numericUserId, socket.id);
            this.userSockets.set(socket.id, numericUserId);

            // Join user's personal room
            socket.join(`user_${numericUserId}`);

            // Update user online status in database
            await DatabaseHelper.updateUserOnlineStatus(numericUserId, true);

            socket.emit('authenticated', {
                success: true,
                message: 'Successfully authenticated',
                userId: numericUserId,
                socketId: socket.id
            });

            logger.info(`User ${numericUserId} authenticated on socket ${socket.id}`);
        } catch (error) {
            logger.error('Connection authentication error:', error);
            socket.emit('auth_error', {
                message: 'Authentication failed',
                code: 'AUTH_ERROR'
            });
        }
    }

    /**
     * Handle user disconnection
     */
    handleDisconnect(socket) {
        const userId = SocketHelper.getUserIdFromSocket(this.userSockets, socket.id);

        if (userId) {
            SocketHelper.cleanupUserConnections(this.connectedUsers, this.userSockets, userId);

            // Update user offline status
            DatabaseHelper.updateUserOnlineStatus(userId, false);

            logger.info(`User ${userId} disconnected from socket ${socket.id}`);
        }
    }

        /**
     * Handle joining chat room
     */
    handleJoinChatRoom(socket, data) {
        const validation = SocketHelper.validateData(data, ['roomId']);
        if (!validation.isValid) {
            socket.emit('error', {
                message: 'Invalid room data',
                missingFields: validation.missingFields
            });
            return;
        }

        const { roomId } = data;
        const userId = SocketHelper.getUserIdFromSocket(this.userSockets, socket.id);

        if (!userId) {
            socket.emit('error', {
                message: 'User not authenticated',
                code: 'NOT_AUTHENTICATED'
            });
            return;
        }

        socket.join(`chat_${roomId}`);

        // Track room participants
        SocketHelper.addToRoomParticipants(this.roomParticipants, roomId, socket.id);

        socket.emit('joined_chat_room', { roomId });
        SocketHelper.broadcastToRoom(socket, `chat_${roomId}`, 'user_joined_chat', { userId, roomId });

        logger.info(`User ${userId} joined chat room ${roomId}`);
    }

        /**
     * Handle leaving chat room
     */
    handleLeaveChatRoom(socket, data) {
        const validation = SocketHelper.validateData(data, ['roomId']);
        if (!validation.isValid) {
            socket.emit('error', {
                message: 'Invalid room data',
                missingFields: validation.missingFields
            });
            return;
        }

        const { roomId } = data;
        const userId = SocketHelper.getUserIdFromSocket(this.userSockets, socket.id);

        if (!userId) {
            socket.emit('error', {
                message: 'User not authenticated',
                code: 'NOT_AUTHENTICATED'
            });
            return;
        }

        socket.leave(`chat_${roomId}`);

        // Remove from room participants
        SocketHelper.removeFromRoomParticipants(this.roomParticipants, roomId, socket.id);

        socket.emit('left_chat_room', { roomId });
        SocketHelper.broadcastToRoom(socket, `chat_${roomId}`, 'user_left_chat', { userId, roomId });

        logger.info(`User ${userId} left chat room ${roomId}`);
    }

        /**
     * Handle sending message
     */
    async handleSendMessage(socket, data) {
        try {
            const validation = SocketHelper.validateData(data, ['roomId', 'receiverId', 'message']);
            if (!validation.isValid) {
                socket.emit('error', {
                    message: 'Invalid message data',
                    missingFields: validation.missingFields
                });
                return;
            }

            const { roomId, receiverId, message, messageType = 'text' } = data;
            const senderId = SocketHelper.getUserIdFromSocket(this.userSockets, socket.id);

            if (!senderId) {
                socket.emit('error', {
                    message: 'User not authenticated',
                    code: 'NOT_AUTHENTICATED'
                });
                return;
            }

            const messageData = {
                id: Date.now().toString(),
                roomId,
                senderId,
                receiverId,
                message,
                messageType,
                timestamp: new Date().toISOString()
            };

            // Save message to database
            await DatabaseHelper.saveChatMessage(messageData);

            // Broadcast to room
            SocketHelper.broadcastToRoom(socket, `chat_${roomId}`, 'new_message', messageData);

            // Send confirmation to sender
            socket.emit('message_sent', messageData);

            logger.info(`Message sent in room ${roomId} by user ${senderId}`);
        } catch (error) {
            logger.error('Error sending message:', error);
            socket.emit('error', { message: 'Failed to send message' });
        }
    }

        /**
     * Handle typing start
     */
    handleTypingStart(socket, data) {
        const validation = SocketHelper.validateData(data, ['roomId']);
        if (!validation.isValid) {
            socket.emit('error', {
                message: 'Invalid typing data',
                missingFields: validation.missingFields
            });
            return;
        }

        const { roomId } = data;
        const userId = SocketHelper.getUserIdFromSocket(this.userSockets, socket.id);

        if (!userId) {
            socket.emit('error', {
                message: 'User not authenticated',
                code: 'NOT_AUTHENTICATED'
            });
            return;
        }

        SocketHelper.broadcastToRoom(socket, `chat_${roomId}`, 'user_typing_start', { userId, roomId });
    }

    /**
     * Handle typing stop
     */
    handleTypingStop(socket, data) {
        const validation = SocketHelper.validateData(data, ['roomId']);
        if (!validation.isValid) {
            socket.emit('error', {
                message: 'Invalid typing data',
                missingFields: validation.missingFields
            });
            return;
        }

        const { roomId } = data;
        const userId = SocketHelper.getUserIdFromSocket(this.userSockets, socket.id);

        if (!userId) {
            socket.emit('error', {
                message: 'User not authenticated',
                code: 'NOT_AUTHENTICATED'
            });
            return;
        }

        SocketHelper.broadcastToRoom(socket, `chat_${roomId}`, 'user_typing_stop', { userId, roomId });
    }





    /**
     * Get connected users count
     */
    getConnectedUsersCount() {
        return SocketHelper.getConnectedUsersCount(this.connectedUsers);
    }

    /**
     * Get server statistics
     */
    getServerStats() {
        return SocketHelper.getServerStats(this.connectedUsers, this.roomParticipants);
    }
}

module.exports = SocketService;
