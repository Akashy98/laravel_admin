module.exports = {
  apps: [
    {
      name: 'astroindia-node-server',
      script: 'server.js',
      instances: 'max', // Use all available CPU cores
      exec_mode: 'cluster',
      env: {
        NODE_ENV: 'development',
        PORT: 3001
      },
      env_production: {
        NODE_ENV: 'production',
        PORT: 3001
      },
      // Logging
      log_file: './logs/combined.log',
      out_file: './logs/out.log',
      error_file: './logs/error.log',
      log_date_format: 'YYYY-MM-DD HH:mm:ss Z',

      // Performance
      max_memory_restart: '1G',
      node_args: '--max-old-space-size=1024',

      // Restart policy
      autorestart: true,
      watch: false,
      max_restarts: 10,
      min_uptime: '10s',

      // Environment variables
      env_file: '.env',

      // Monitoring
      merge_logs: true,

      // Graceful shutdown
      kill_timeout: 5000,
      listen_timeout: 3000,

      // Health check
      health_check_grace_period: 3000,

      // Advanced settings
      source_map_support: false,
      disable_source_map_support: true,

      // Error handling
      ignore_watch: [
        'node_modules',
        'logs',
        '*.log'
      ],

      // Development settings
      watch_delay: 1000,
      ignore_watch_delay: 2000
    }
  ],

  deploy: {
    production: {
      user: 'node',
      host: 'your-server-ip',
      ref: 'origin/main',
      repo: 'git@github.com:your-username/astroindia-backend.git',
      path: '/var/www/astroindia-backend',
      'pre-deploy-local': '',
      'post-deploy': 'npm install && pm2 reload ecosystem.config.js --env production',
      'pre-setup': ''
    }
  }
};
