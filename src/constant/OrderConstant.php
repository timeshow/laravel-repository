<?php
namespace TimeShow\Repository\Constant;

final class OrderConstant
{
    // Status constants
    public const STATUS_NONE = 0; //无
    public const STATUS_PENDING = 1; //1待付款
    public const STATUS_PROCESSING = 2; //2待发货
    public const STATUS_SHIPPED = 3; //3已发货
    public const STATUS_DELIVERED = 4; //4已签收(待评价)
    public const STATUS_FINISH = 5; //5已完成
    public const STATUS_CANCELLED = 6; //6已取消
    public const STATUS_REFUNDED = 7; // 7已关闭(管理员)'

    // Type constants
    public const TYPE_STANDARD = 'standard';
    public const TYPE_EXPRESS = 'express';
    public const TYPE_INTERNATIONAL = 'international';

    // Payment method constants
    public const PAYMENT_CASH = 'cash';
    public const PAYMENT_CREDIT_CARD = 'credit_card';
    public const PAYMENT_PAYPAL = 'paypal';
    public const PAYMENT_BANK_TRANSFER = 'bank_transfer';

    // Shipping method constants
    public const SHIPPING_STANDARD = 'standard';
    public const SHIPPING_EXPRESS = 'express';
    public const SHIPPING_PICKUP = 'pickup';

    /**
     * Get all available statuses
     */
    public static function getAllStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_SHIPPED,
            self::STATUS_DELIVERED,
            self::STATUS_CANCELLED,
            self::STATUS_REFUNDED,
        ];
    }

    /**
     * Get status transition map
     */
    public static function getStatusTransitions(string $currentStatus): array
    {
        $transitions = [
            self::STATUS_PENDING => [
                self::STATUS_PROCESSING,
                self::STATUS_CANCELLED,
            ],
            self::STATUS_PROCESSING => [
                self::STATUS_SHIPPED,
                self::STATUS_CANCELLED,
            ],
            self::STATUS_SHIPPED => [
                self::STATUS_DELIVERED,
                self::STATUS_REFUNDED,
            ],
            self::STATUS_DELIVERED => [
                self::STATUS_REFUNDED,
            ],
        ];

        return $transitions[$currentStatus] ?? [];
    }

    /**
     * Check if status transition is valid
     */
    public static function isValidTransition(string $from, string $to): bool
    {
        return in_array($to, self::getStatusTransitions($from), true);
    }
}