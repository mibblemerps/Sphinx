<?php

namespace Sphinx;

/*
 * The MIT License
 *
 * Copyright 2015 Mitchfizz05.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Minecraft Realms open-source alternative server.
 *
 * @author Mitchfizz05
 */
class Realms {
    const VERSION = '1.0.0';
    const DEVBUILD = true;
    
    /**
     * Realms logger.
     * @var Logger
     */
    public static $logger;
    
    /**
     * Realms configuration.
     * @var Config
     */
    public static $config;
    
    public static $hasinit = false; // has Realms been init..?
    
    /**
     * Realms request registry.
     * @var RequestRegistry
     */
    public static $requestRegistry;
    
    public static function init() {
        if (self::$hasinit) { return false; }

        // Load configuration.
        self::$config = new Config('realms.ini');
        if (!self::$config->get('general', 'service_requests')) { // Service unavaliable. :(
            http_response_code(503); // 503 Service Unavaliable.
            echo 'service unavaliable';
            exit; // terminate here.
        }

        // Load logger.
        self::$logger = new Logger(self::$config->get('general', 'log_path'));

        // Load request registry
        self::$requestRegistry = new RequestRegistry(); // create request registry instance
        
        // Dynamically load request handlers.
        $handler_files = scandir('inc/Controllers');
        foreach ($handler_files as $handler_file) {
            if ($handler_file == '.' ||
                $handler_file == '..' ||
                $handler_file == 'Controller.php') { continue; }
            
            // Register handler
            $classname = 'Sphinx\\Controllers\\' . pathinfo($handler_file, PATHINFO_FILENAME);
            self::$requestRegistry->register(new $classname);
        }
        
        // Realms init finish.
        self::$hasinit = true;
        return true;
    }
}
