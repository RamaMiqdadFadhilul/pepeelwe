<?php

class Notification
{
    private int $id;
    private int $userId;
    private string $title;
    private string $body;
    private string $createdAt;
    private bool $isRead;

    public function __construct(
        int $id,
        int $userId,
        string $title,
        string $body,
        string $createdAt,
        bool $isRead
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->title = $title;
        $this->body = $body;
        $this->createdAt = $createdAt;
        $this->isRead = $isRead;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public static function create(
        int $userId,
        string $title,
        string $body
    ): self {

        $db = new DBconnection();

        $respon = $db->send_query(
            'INSERT INTO notifications
            (user_id, title, body)
            VALUES ($1, $2, $3)
            RETURNING id, user_id, title, body,
                      created_at, is_read',
            [
                $userId,
                $title,
                $body
            ]
        );

        $db->close_connection();

        if (!$respon->status) {
            throw new DatabaseException($respon->message);
        }

        $row = $respon->data[0];

        return new self(
            (int) $row['id'],
            (int) $row['user_id'],
            $row['title'],
            $row['body'],
            $row['created_at'],
            $row['is_read'] === true ||
            $row['is_read'] === 't' ||
            $row['is_read'] === '1'
        );
    }

    public static function find(int $id): ?self
    {
        $db = new DBconnection();

        $respon = $db->send_query(
            'SELECT id, user_id, title, body,
                    created_at, is_read
             FROM notifications
             WHERE id = $1',
            [$id]
        );

        $db->close_connection();

        if (!$respon->status) {
            throw new DatabaseException($respon->message);
        }

        if (count($respon->data) === 0) {
            return null;
        }

        $row = $respon->data[0];

        return new self(
            (int) $row['id'],
            (int) $row['user_id'],
            $row['title'],
            $row['body'],
            $row['created_at'],
            $row['is_read'] === true ||
            $row['is_read'] === 't' ||
            $row['is_read'] === '1'
        );
    }

    public static function findByUser(
        int $userId
    ): array {

        $db = new DBconnection();

        $respon = $db->send_query(
            'SELECT id, user_id, title, body,
                    created_at, is_read
             FROM notifications
             WHERE user_id = $1
             ORDER BY created_at DESC, id DESC',
            [$userId]
        );

        $db->close_connection();

        if (!$respon->status) {
            throw new DatabaseException($respon->message);
        }

        $list = [];

        foreach ($respon->data as $row) {

            $list[] = new self(
                (int) $row['id'],
                (int) $row['user_id'],
                $row['title'],
                $row['body'],
                $row['created_at'],
                $row['is_read'] === true ||
                $row['is_read'] === 't' ||
                $row['is_read'] === '1'
            );
        }

        return $list;
    }

    public function update(
        string $title,
        string $body
    ): void {

        $db = new DBconnection();

        $respon = $db->send_query(
            'UPDATE notifications
             SET title = $1,
                 body = $2
             WHERE id = $3',
            [
                $title,
                $body,
                $this->id
            ]
        );

        $db->close_connection();

        if (!$respon->status) {
            throw new DatabaseException($respon->message);
        }

        $this->title = $title;
        $this->body = $body;
    }

    public function markAsRead(): void
    {
        $db = new DBconnection();

        $respon = $db->send_query(
            'UPDATE notifications
             SET is_read = TRUE
             WHERE id = $1',
            [$this->id]
        );

        $db->close_connection();

        if (!$respon->status) {
            throw new DatabaseException($respon->message);
        }

        $this->isRead = true;
    }

    public function delete(): void
    {
        $db = new DBconnection();

        $respon = $db->send_query(
            'DELETE FROM notifications
             WHERE id = $1',
            [$this->id]
        );

        $db->close_connection();

        if (!$respon->status) {
            throw new DatabaseException($respon->message);
        }
    }

    public static function unread(array $list): array
    {
        return array_values(
            array_filter(
                $list,
                fn ($notification) =>
                    $notification instanceof self &&
                    !$notification->isRead()
            )
        );
    }

    public static function countUnread(array $list): int
    {
        return count(self::unread($list));
    }

    public function __toString(): string
    {
        return "[" . $this->title . "] - " .
            ($this->isRead ? "READ" : "UNREAD");
    }
}
