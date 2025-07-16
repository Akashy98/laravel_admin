# AstroIndia Node.js Server

A real-time Node.js server with Socket.IO for chat and call functionality, built for the AstroIndia platform.

## Features

- 🔐 **Authentication**: JWT-based authentication system
- 💬 **Real-time Chat**: Socket.IO powered chat functionality
- 📞 **Voice/Video Calls**: WebRTC-based call system
- 🗄️ **Database Pooling**: Optimized MySQL connection pooling
- 📊 **PM2 Process Management**: Production-ready process management
- 🔒 **Security**: Helmet, CORS, Rate limiting
- 📝 **Logging**: Winston structured logging
- 🚀 **Performance**: Compression, clustering

## Project Structure

```
node/
├── config/
│   └── database.js          # Database connection with pooling
├── helpers/
│   ├── auth.js             # Authentication utilities
│   ├── logger.js           # Winston logger configuration
│   └── response.js         # API response helpers
├── routes/
│   ├── auth.js            # Authentication routes
│   ├── users.js           # User management routes
│   ├── chat.js            # Chat functionality routes
│   └── calls.js           # Call functionality routes
├── services/
│   └── socketService.js   # Socket.IO service for real-time features
├── logs/                  # Application logs (auto-created)
├── server.js              # Main server file
├── ecosystem.config.js    # PM2 configuration
├── package.json           # Dependencies and scripts
├── env.example           # Environment variables template
└── README.md             # This file
```

## Prerequisites

- Node.js >= 16.0.0
- MySQL >= 5.7
- PM2 (for production)

## Installation

1. **Clone and navigate to the node directory:**
   ```bash
   cd node
   ```

2. **Install dependencies:**
   ```bash
   npm install
   ```

3. **Set up environment variables:**
   ```bash
   cp env.example .env
   ```
   Edit `.env` file with your configuration:
   ```env
   # Server Configuration
   NODE_ENV=development
   PORT=3001
   HOST=localhost

   # Database Configuration
   DB_HOST=localhost
   DB_PORT=3306
   DB_USER=root
   DB_PASSWORD=your_password
   DB_NAME=astroindia_backend
   DB_POOL_MIN=5
   DB_POOL_MAX=20

   # JWT Configuration
   JWT_SECRET=your-super-secret-jwt-key-change-this-in-production
   JWT_EXPIRES_IN=24h

   # Socket.IO Configuration
   SOCKET_CORS_ORIGIN=http://localhost:3000
   SOCKET_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:3001
   ```

4. **Create required database tables:**
   ```sql
   -- Chat messages table
   CREATE TABLE IF NOT EXISTS chat_messages (
       id BIGINT PRIMARY KEY AUTO_INCREMENT,
       sender_id BIGINT NOT NULL,
       receiver_id BIGINT NOT NULL,
       message TEXT NOT NULL,
       message_type ENUM('text', 'image', 'file') DEFAULT 'text',
       is_read BOOLEAN DEFAULT FALSE,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       FOREIGN KEY (sender_id) REFERENCES users(id),
       FOREIGN KEY (receiver_id) REFERENCES users(id),
       INDEX idx_sender_receiver (sender_id, receiver_id),
       INDEX idx_receiver_sender (receiver_id, sender_id),
       INDEX idx_created_at (created_at)
   );

   -- Calls table
   CREATE TABLE IF NOT EXISTS calls (
       id BIGINT PRIMARY KEY AUTO_INCREMENT,
       caller_id BIGINT NOT NULL,
       receiver_id BIGINT NOT NULL,
       call_type ENUM('audio', 'video') DEFAULT 'audio',
       status ENUM('initiated', 'active', 'ended', 'rejected', 'missed') DEFAULT 'initiated',
       duration INT DEFAULT 0,
       start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       accept_time TIMESTAMP NULL,
       end_time TIMESTAMP NULL,
       reject_reason TEXT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       FOREIGN KEY (caller_id) REFERENCES users(id),
       FOREIGN KEY (receiver_id) REFERENCES users(id),
       INDEX idx_caller (caller_id),
       INDEX idx_receiver (receiver_id),
       INDEX idx_status (status),
       INDEX idx_start_time (start_time)
   );

   -- Add is_online column to users table if not exists
   ALTER TABLE users ADD COLUMN IF NOT EXISTS is_online BOOLEAN DEFAULT FALSE;
   ALTER TABLE users ADD COLUMN IF NOT EXISTS last_seen TIMESTAMP NULL;
   ```

## Development

### Start development server:
```bash
npm run dev
```

### Start production server:
```bash
npm start
```

## PM2 Process Management

### Install PM2 globally:
```bash
npm install -g pm2
```

### Start with PM2:
```bash
npm run pm2:start
```

### Other PM2 commands:
```bash
npm run pm2:stop      # Stop the application
npm run pm2:restart   # Restart the application
npm run pm2:delete    # Delete the application from PM2
npm run pm2:logs      # View logs
npm run pm2:status    # Check status
```

## API Endpoints

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `POST /api/auth/verify` - Verify JWT token
- `POST /api/auth/refresh` - Refresh JWT token

### Users
- `GET /api/users/profile` - Get user profile
- `PUT /api/users/profile` - Update user profile
- `GET /api/users/astrologers` - Get astrologers list
- `GET /api/users/astrologers/:id` - Get astrologer details
- `GET /api/users/online` - Get online users count

### Chat
- `GET /api/chat/conversations` - Get user conversations
- `GET /api/chat/messages/:userId` - Get messages with user
- `POST /api/chat/messages` - Send message
- `DELETE /api/chat/messages/:messageId` - Delete message
- `GET /api/chat/unread-count` - Get unread count
- `PUT /api/chat/messages/:userId/read` - Mark messages as read

### Calls
- `GET /api/calls/history` - Get call history
- `POST /api/calls/initiate` - Initiate call
- `PUT /api/calls/:callId/accept` - Accept call
- `PUT /api/calls/:callId/reject` - Reject call
- `PUT /api/calls/:callId/end` - End call
- `GET /api/calls/:callId` - Get call details
- `GET /api/calls/astrologers/online` - Get online astrologers

### Health Check
- `GET /health` - Server health status

## Socket.IO Events

### Authentication
- `authenticate` - Authenticate user with token
- `authenticated` - Authentication successful
- `auth_error` - Authentication failed

### Chat Events
- `join_chat_room` - Join chat room
- `leave_chat_room` - Leave chat room
- `send_message` - Send message
- `new_message` - Receive new message
- `typing_start` - User started typing
- `typing_stop` - User stopped typing

### Call Events
- `initiate_call` - Initiate call
- `incoming_call` - Receive incoming call
- `accept_call` - Accept call
- `reject_call` - Reject call
- `end_call` - End call
- `join_call_room` - Join call room
- `leave_call_room` - Leave call room

### WebRTC Signaling
- `offer` - WebRTC offer
- `answer` - WebRTC answer
- `ice_candidate` - ICE candidate

## Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `NODE_ENV` | Environment mode | `development` |
| `PORT` | Server port | `3001` |
| `HOST` | Server host | `localhost` |
| `DB_HOST` | Database host | `localhost` |
| `DB_PORT` | Database port | `3306` |
| `DB_USER` | Database user | `root` |
| `DB_PASSWORD` | Database password | `` |
| `DB_NAME` | Database name | `astroindia_backend` |
| `DB_POOL_MIN` | Min connections | `5` |
| `DB_POOL_MAX` | Max connections | `20` |
| `JWT_SECRET` | JWT secret key | Required |
| `JWT_EXPIRES_IN` | Token expiration | `24h` |
| `SOCKET_CORS_ORIGIN` | Socket CORS origin | `http://localhost:3000` |
| `LOG_LEVEL` | Log level | `info` |
| `RATE_LIMIT_WINDOW_MS` | Rate limit window | `900000` (15 min) |
| `RATE_LIMIT_MAX_REQUESTS` | Max requests per window | `100` |

## Database Schema

The server expects the following tables to exist:
- `users` - User accounts
- `user_profiles` - User profile information
- `astrologers` - Astrologer profiles
- `chat_messages` - Chat messages
- `calls` - Call records

## Security Features

- **Helmet**: Security headers
- **CORS**: Cross-origin resource sharing
- **Rate Limiting**: API rate limiting
- **JWT**: Secure token-based authentication
- **Input Validation**: Request validation
- **SQL Injection Protection**: Parameterized queries

## Performance Features

- **Connection Pooling**: Database connection pooling
- **Compression**: Response compression
- **Clustering**: PM2 cluster mode
- **Memory Management**: Automatic memory cleanup
- **Graceful Shutdown**: Proper cleanup on exit

## Monitoring

- **Winston Logging**: Structured logging
- **PM2 Monitoring**: Process monitoring
- **Health Checks**: Server health endpoints
- **Error Tracking**: Comprehensive error handling

## Future Enhancements

- [ ] File upload functionality
- [ ] Push notifications
- [ ] Message encryption
- [ ] Call recording
- [ ] Analytics dashboard
- [ ] Multi-language support
- [ ] Payment integration
- [ ] Admin panel

## Troubleshooting

### Common Issues

1. **Database Connection Failed**
   - Check database credentials in `.env`
   - Ensure MySQL is running
   - Verify database exists

2. **Port Already in Use**
   - Change PORT in `.env`
   - Kill existing process: `lsof -ti:3001 | xargs kill -9`

3. **PM2 Issues**
   - Check PM2 logs: `pm2 logs`
   - Restart PM2: `pm2 restart all`
   - Delete and restart: `pm2 delete all && pm2 start ecosystem.config.js`

4. **Socket.IO Connection Issues**
   - Check CORS settings
   - Verify client connection URL
   - Check firewall settings

## Support

For issues and questions:
1. Check the logs in `logs/` directory
2. Review PM2 status: `pm2 status`
3. Check server health: `GET /health`

## License

MIT License - see LICENSE file for details. 
