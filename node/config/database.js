const mysql = require('mysql2/promise');
require('dotenv').config();

class Database {
    constructor() {
        this.pool = null;
        this.connection = null;
    }

    async createPool() {
        try {
            this.pool = mysql.createPool({
                host: process.env.DB_HOST || 'localhost',
                port: process.env.DB_PORT || 3306,
                user: process.env.DB_USER || 'root',
                password: process.env.DB_PASSWORD || '',
                database: process.env.DB_NAME || 'astroindia_backend',
                waitForConnections: true,
                connectionLimit: parseInt(process.env.DB_POOL_MAX) || 20,
                queueLimit: 0,
                // acquireTimeout: 60000,
                // timeout: 60000,
                // reconnect: true,
                charset: 'utf8mb4',
                timezone: '+00:00'
            });

            // Test the connection
            const connection = await this.pool.getConnection();
            console.log('✅ Database connection pool created successfully');
            connection.release();

            return this.pool;
        } catch (error) {
            console.error('❌ Database connection failed:', error.message);
            throw error;
        }
    }

    async getConnection() {
        if (!this.pool) {
            await this.createPool();
        }
        return await this.pool.getConnection();
    }

    async query(sql, params = []) {
        try {
            const [rows] = await this.pool.execute(sql, params);
            return rows;
        } catch (error) {
            console.error('Database query error:', error);
            throw error;
        }
    }

    async transaction(callback) {
        const connection = await this.getConnection();
        try {
            await connection.beginTransaction();
            const result = await callback(connection);
            await connection.commit();
            return result;
        } catch (error) {
            await connection.rollback();
            throw error;
        } finally {
            connection.release();
        }
    }

    async close() {
        if (this.pool) {
            await this.pool.end();
            console.log('Database connection pool closed');
        }
    }

    getPoolStatus() {
        if (!this.pool) {
            return { status: 'not_initialized' };
        }

        return {
            status: 'active',
            threadId: this.pool.threadId,
            connectionLimit: this.pool.config.connectionLimit,
            queueLimit: this.pool.config.queueLimit
        };
    }
}

// Create singleton instance
const database = new Database();

module.exports = database;
