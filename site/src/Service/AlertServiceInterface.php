<?php

namespace App\Service;

/**
 * Interface AlertServiceInterface
 * @package App\Service
 */
interface AlertServiceInterface
{
    /**
     * Primary alert bootstrap.
     * @param string $message
     */
    public function primary(string $message): void;

    /**
     * Secondary alert bootstrap.
     * @param string $message
     */
    public function secondary(string $message): void;

    /**
     * Success alert bootstrap.
     * @param string $message
     */
    public function success(string $message): void;

    /**
     * Danger alert bootstrap.
     * @param string $message
     */
    public function danger(string $message): void;

    /**
     * Warning alert bootstrap.
     * @param string $message
     */
    public function warning(string $message): void;

    /**
     * Info alert bootstrap.
     * @param string $message
     */
    public function info(string $message): void;

    /**
     * Light alert bootstrap.
     * @param string $message
     */
    public function light(string $message): void;

    /**
     * Dark alert bootstrap.
     * @param string $message
     */
    public function dark(string $message): void;
}
