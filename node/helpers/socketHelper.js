const logger = require('./logger');

class SocketHelper {
    /**
     * Send notification to a specific user
     * @param {Object} io - Socket.io instance
     * @param {Map} connectedUsers - Map of connected users
     * @param {number} userId - Target user ID
     * @param {string} event - Event name
     * @param {Object} data - Data to send
     * @returns {boolean} - Whether the notification was sent
     */
    static sendToUser(io, connectedUsers, userId, event, data) {
        const socketId = connectedUsers.get(userId);
        if (socketId) {
            io.to(socketId).emit(event, data);
            logger.info(`Sent ${event} to user ${userId}`);
            return true;
        }
        logger.warn(`User ${userId} not connected for event ${event}`);
        return false;
    }

    /**
     * Send notification to multiple users
     * @param {Object} io - Socket.io instance
     * @param {Map} connectedUsers - Map of connected users
     * @param {Array} userIds - Array of user IDs
     * @param {string} event - Event name
     * @param {Object} data - Data to send
     * @returns {Array} - Array of user IDs that received the notification
     */
    static sendToUsers(io, connectedUsers, userIds, event, data) {
        const sentTo = [];
        userIds.forEach(userId => {
            if (this.sendToUser(io, connectedUsers, userId, event, data)) {
                sentTo.push(userId);
            }
        });
        return sentTo;
    }

    /**
     * Broadcast to room excluding sender
     * @param {Object} socket - Socket instance
     * @param {string} room - Room name
     * @param {string} event - Event name
     * @param {Object} data - Data to send
     */
    static broadcastToRoom(socket, room, event, data) {
        socket.to(room).emit(event, data);
        logger.info(`Broadcasted ${event} to room ${room}`);
    }

    /**
     * Broadcast to room including sender
     * @param {Object} socket - Socket instance
     * @param {string} room - Room name
     * @param {string} event - Event name
     * @param {Object} data - Data to send
     */
    static broadcastToRoomIncludingSender(socket, room, event, data) {
        socket.in(room).emit(event, data);
        logger.info(`Broadcasted ${event} to room ${room} including sender`);
    }

    /**
     * Validate socket data
     * @param {Object} data - Data to validate
     * @param {Array} requiredFields - Array of required field names
     * @returns {Object} - Validation result
     */
    static validateData(data, requiredFields) {
        const missingFields = [];

        requiredFields.forEach(field => {
            if (!data || data[field] === undefined || data[field] === null) {
                missingFields.push(field);
            }
        });

        return {
            isValid: missingFields.length === 0,
            missingFields
        };
    }

    /**
     * Generate unique room ID
     * @param {string} prefix - Room prefix
     * @param {Array} participants - Array of participant IDs
     * @returns {string} - Generated room ID
     */
    static generateRoomId(prefix, participants) {
        const sortedParticipants = participants.sort();
        return `${prefix}_${sortedParticipants.join('_')}`;
    }



    /**
     * Check if user is connected
     * @param {Map} connectedUsers - Map of connected users
     * @param {number} userId - User ID to check
     * @returns {boolean} - Whether user is connected
     */
    static isUserConnected(connectedUsers, userId) {
        return connectedUsers.has(userId);
    }

    /**
     * Get connected users count
     * @param {Map} connectedUsers - Map of connected users
     * @returns {number} - Number of connected users
     */
    static getConnectedUsersCount(connectedUsers) {
        return connectedUsers.size;
    }

    /**
     * Get user socket ID
     * @param {Map} connectedUsers - Map of connected users
     * @param {number} userId - User ID
     * @returns {string|null} - Socket ID or null if not found
     */
    static getUserSocketId(connectedUsers, userId) {
        return connectedUsers.get(userId) || null;
    }

    /**
     * Get user ID from socket ID
     * @param {Map} userSockets - Map of socket IDs to user IDs
     * @param {string} socketId - Socket ID
     * @returns {number|null} - User ID or null if not found
     */
    static getUserIdFromSocket(userSockets, socketId) {
        return userSockets.get(socketId) || null;
    }

    /**
     * Clean up user connections
     * @param {Map} connectedUsers - Map of connected users
     * @param {Map} userSockets - Map of socket IDs to user IDs
     * @param {number} userId - User ID to clean up
     */
    static cleanupUserConnections(connectedUsers, userSockets, userId) {
        const socketId = connectedUsers.get(userId);
        if (socketId) {
            connectedUsers.delete(userId);
            userSockets.delete(socketId);
            logger.info(`Cleaned up connections for user ${userId}`);
        }
    }

    /**
     * Get room participants count
     * @param {Map} roomParticipants - Map of room participants
     * @param {string} roomId - Room ID
     * @returns {number} - Number of participants
     */
    static getRoomParticipantsCount(roomParticipants, roomId) {
        const participants = roomParticipants.get(roomId);
        return participants ? participants.size : 0;
    }

    /**
     * Add user to room participants
     * @param {Map} roomParticipants - Map of room participants
     * @param {string} roomId - Room ID
     * @param {string} socketId - Socket ID
     */
    static addToRoomParticipants(roomParticipants, roomId, socketId) {
        if (!roomParticipants.has(roomId)) {
            roomParticipants.set(roomId, new Set());
        }
        roomParticipants.get(roomId).add(socketId);
    }

    /**
     * Remove user from room participants
     * @param {Map} roomParticipants - Map of room participants
     * @param {string} roomId - Room ID
     * @param {string} socketId - Socket ID
     */
    static removeFromRoomParticipants(roomParticipants, roomId, socketId) {
        if (roomParticipants.has(roomId)) {
            roomParticipants.get(roomId).delete(socketId);
        }
    }

    /**
     * Get server statistics
     * @param {Map} connectedUsers - Map of connected users
     * @param {Map} roomParticipants - Map of room participants
     * @returns {Object} - Server statistics
     */
    static getServerStats(connectedUsers, roomParticipants) {
        return {
            connectedUsers: connectedUsers.size,
            totalRooms: roomParticipants.size,
            timestamp: new Date().toISOString()
        };
    }
}

module.exports = SocketHelper;
