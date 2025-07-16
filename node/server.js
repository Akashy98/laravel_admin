const http = require('http');
const socketIo = require('socket.io');
require('dotenv').config();

const logger = require('./helpers/logger');
const database = require('./config/database');
const SocketService = require('./services/socketService');

const server = http.createServer();

// Socket.IO setup with CORS
const io = socketIo(server, {
    cors: {
        origin: process.env.SOCKET_ALLOWED_ORIGINS?.split(',') || ["http://localhost:3000"],
        methods: ["GET", "POST"],
        credentials: true
    },
    transports: ['websocket', 'polling']
});

// Initialize socket service
const socketService = new SocketService(io);
socketService.initialize();

// Graceful shutdown
process.on('SIGTERM', async () => {
    logger.info('SIGTERM received, shutting down gracefully');
    await database.close();
    server.close(() => {
        logger.info('Server closed');
        process.exit(0);
    });
});

process.on('SIGINT', async () => {
    logger.info('SIGINT received, shutting down gracefully');
    await database.close();
    server.close(() => {
        logger.info('Server closed');
        process.exit(0);
    });
});

// Initialize database and start server
async function startServer() {
    try {
        // Initialize database connection
        await database.createPool();

        const PORT = process.env.PORT || 3001;
        const HOST = process.env.HOST || 'localhost';

        server.listen(PORT, HOST, () => {
            logger.info(`🚀 Socket server running on http://${HOST}:${PORT}`);
            logger.info(`📊 Environment: ${process.env.NODE_ENV || 'development'}`);
            logger.info(`🔌 Socket.IO server initialized`);
        });
    } catch (error) {
        logger.error('Failed to start server:', error);
        process.exit(1);
    }
}

startServer();

module.exports = { server, io, socketService };
