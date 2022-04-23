<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class AlertService
 * @package App\Service
 */
class AlertService implements AlertServiceInterface
{
    public const ALERT_PRIMARY = "primary";
    public const ALERT_SECONDARY = "secondary";
    public const ALERT_SUCCESS = "success";
    public const ALERT_DANGER = "danger";
    public const ALERT_WARNING = "warning";
    public const ALERT_INFO = "info";
    public const ALERT_LIGHT = "light";
    public const ALERT_DARK = "dark";

    /**
     * @var Session
     */
    private Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @inheritDoc
     */
    public function primary(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_PRIMARY, $message);
    }

    /**
     * @inheritDoc
     */
    public function secondary(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_SECONDARY, $message);
    }

    /**
     * @inheritDoc
     */
    public function success(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_SUCCESS, $message);
    }

    /**
     * @inheritDoc
     */
    public function danger(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_DANGER, $message);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_WARNING, $message);
    }

    /**
     * @inheritDoc
     */
    public function info(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_INFO, $message);
    }

    /**
     * @inheritDoc
     */
    public function light(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_LIGHT, $message);
    }

    /**
     * @inheritDoc
     */
    public function dark(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_DARK, $message);
    }
}
