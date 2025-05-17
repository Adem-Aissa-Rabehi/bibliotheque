<?php
class Logger {
    private $logFile;
    
    public function __construct() {
        $this->logFile = __DIR__ . '/../logs/admin_actions.log';
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0777, true);
        }
    }
    
    public function log($adminId, $action, $details) {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] Admin ID: $adminId | Action: $action | Details: $details\n";
        file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }
    
    public function getLogs($limit = 100) {
        if (!file_exists($this->logFile)) {
            return [];
        }
        
        $logs = array_reverse(file($this->logFile));
        return array_slice($logs, 0, $limit);
    }
}
?> 