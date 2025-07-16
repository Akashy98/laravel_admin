const database = require('../config/database');
const logger = require('./logger');

class DatabaseHelper {
    /**
     * Update user online status
     * @param {number} userId - User ID
     * @param {boolean} isOnline - Online status
     * @returns {Promise<void>}
     */
    static async updateUserOnlineStatus(userId, isOnline) {
        try {
            const sql = 'UPDATE users SET is_online = ?, last_seen = NOW() WHERE id = ?';
            await database.query(sql, [isOnline ? 1 : 0, userId]);
            logger.info(`Updated user ${userId} online status to ${isOnline}`);
        } catch (error) {
            logger.error('Error updating user online status:', error);
            throw error;
        }
    }

    /**
     * Save chat message to database
     * @param {Object} messageData - Message data object
     * @param {string} messageData.roomId - Room ID
     * @param {number} messageData.senderId - Sender ID
     * @param {number} messageData.receiverId - Receiver ID
     * @param {string} messageData.message - Message content
     * @param {string} messageData.messageType - Message type (text, image, etc.)
     * @param {string} messageData.timestamp - Message timestamp
     * @returns {Promise<void>}
     */
    static async saveChatMessage(messageData) {
        try {
            const sql = `
                INSERT INTO chat_messages (room_id, sender_id, receiver_id, message, message_type, created_at)
                VALUES (?, ?, ?, ?, ?, ?)
            `;

            await database.query(sql, [
                messageData.roomId,
                messageData.senderId,
                messageData.receiverId,
                messageData.message,
                messageData.messageType,
                messageData.timestamp
            ]);

            logger.info(`Saved chat message in room ${messageData.roomId} from user ${messageData.senderId}`);
        } catch (error) {
            logger.error('Error saving chat message to database:', error);
            throw error;
        }
    }



    /**
     * Get user online status
     * @param {number} userId - User ID
     * @returns {Promise<Object>} User online status
     */
    static async getUserOnlineStatus(userId) {
        try {
            const sql = 'SELECT is_online, last_seen FROM users WHERE id = ?';
            const [rows] = await database.query(sql, [userId]);

            if (rows.length > 0) {
                return {
                    isOnline: rows[0].is_online === 1,
                    lastSeen: rows[0].last_seen
                };
            }

            return null;
        } catch (error) {
            logger.error('Error getting user online status:', error);
            throw error;
        }
    }

    /**
     * Get chat messages for a room
     * @param {string} roomId - Room ID
     * @param {number} limit - Number of messages to retrieve
     * @param {number} offset - Offset for pagination
     * @returns {Promise<Array>} Array of chat messages
     */
    static async getChatMessages(roomId, limit = 50, offset = 0) {
        try {
            const sql = `
                SELECT * FROM chat_messages
                WHERE room_id = ?
                ORDER BY created_at DESC
                LIMIT ? OFFSET ?
            `;

            const [rows] = await database.query(sql, [roomId, limit, offset]);
            return rows;
        } catch (error) {
            logger.error('Error getting chat messages:', error);
            throw error;
        }
    }



    /**
     * Delete old chat messages (cleanup)
     * @param {number} daysOld - Number of days old to delete
     * @returns {Promise<number>} Number of deleted records
     */
    static async cleanupOldChatMessages(daysOld = 30) {
        try {
            const sql = `
                DELETE FROM chat_messages
                WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
            `;

            const [result] = await database.query(sql, [daysOld]);
            logger.info(`Cleaned up ${result.affectedRows} old chat messages`);
            return result.affectedRows;
        } catch (error) {
            logger.error('Error cleaning up old chat messages:', error);
            throw error;
        }
    }


}

module.exports = DatabaseHelper;
