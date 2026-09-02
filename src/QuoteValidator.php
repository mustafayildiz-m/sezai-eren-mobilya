<?php
declare(strict_types=1);
namespace App;

final class QuoteValidator
{
    public const MAX_PHOTO_BYTES = 10 * 1024 * 1024;

    /** @return array{errors: array<string,string>, data: array<string,string>} */
    public static function validate(array $post, array $files): array
    {
        $errors = [];
        $data = [
            'name' => trim((string) ($post['name'] ?? '')),
            'phone' => preg_replace('/\D+/', '', (string) ($post['phone'] ?? '')) ?? '',
            'email' => trim((string) ($post['email'] ?? '')),
            'job_type' => mb_substr(trim((string) ($post['job_type'] ?? '')), 0, 80),
            'message' => trim((string) ($post['message'] ?? '')),
        ];

        if (!empty($post['website'])) $errors['spam'] = 'Gönderim doğrulanamadı.';
        if ($data['name'] === '' || mb_strlen($data['name']) > 120) $errors['name'] = 'Lütfen adınızı ve soyadınızı yazın.';
        if (strlen($data['phone']) < 10 || strlen($data['phone']) > 13) $errors['phone'] = 'Lütfen geçerli bir telefon numarası yazın.';
        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'E-posta adresi geçersiz görünüyor.';
        if (mb_strlen($data['message']) > 5000) $errors['message'] = 'Mesaj çok uzun (en fazla 5000 karakter).';

        $photo = $files['photo'] ?? null;
        if ($photo && ($photo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $err = Image::validate($photo, self::MAX_PHOTO_BYTES);
            if ($err) $errors['photo'] = $err;
        }

        return ['errors' => $errors, 'data' => $data];
    }
}
