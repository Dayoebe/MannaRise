<?php

namespace App\Support;

use GdImage;

class DailyOpenGraphCard
{
    public const WIDTH = 1200;

    public const HEIGHT = 630;

    private GdImage $image;

    /**
     * @var array{sans:string,bold:string,serif:string,display:string}
     */
    private array $fonts;

    /**
     * @param  array<string, mixed>  $card
     */
    public static function render(array $card): string
    {
        $renderer = new self;

        return $renderer->draw($card);
    }

    private function __construct()
    {
        $this->image = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        imageantialias($this->image, true);

        $this->fonts = [
            'sans' => $this->font('NotoSans-Regular.ttf'),
            'bold' => $this->font('NotoSans-Bold.ttf'),
            'serif' => $this->font('NotoSerif-Regular.ttf'),
            'display' => $this->font('NotoSerifDisplay-SemiCondensedBold.ttf'),
        ];
    }

    /**
     * @param  array<string, mixed>  $card
     */
    private function draw(array $card): string
    {
        $title = $this->text($card['title'] ?? 'Daily Devotion');
        $date = $this->text($card['date'] ?? '');
        $scripture = $this->text($card['scripture_text'] ?? '');
        $reference = $this->text($card['scripture_reference'] ?? '');
        $affirmation = $this->text($card['affirmation'] ?? '');
        $theme = $this->text($card['theme'] ?? '');
        $language = $this->text($card['language'] ?? '');
        $dailyLabel = $this->text($card['daily_label'] ?? 'Daily devotion');
        $growthLabel = $this->text($card['growth_label'] ?? 'grow daily');
        $appName = $this->text($card['app_name'] ?? 'MannaRise');
        $appHost = $this->text($card['app_host'] ?? $appName);

        $this->drawGradient();
        $this->drawColorStrip();

        $this->roundedRectangle(64, 72, 1072, 500, 42, '#ffffff');
        $this->roundedRectangle(78, 88, 1044, 468, 34, '#ecfdf5', 62);
        $this->roundedStroke(90, 100, 1020, 444, 30, '#a7f3d0', 4);
        $this->roundedStroke(106, 116, 988, 412, 24, '#bfdbfe', 2);

        $this->roundedRectangle(132, 130, 274, 46, 23, '#047857');
        $this->writeText($dailyLabel, 154, 160, 21, '#ffffff', 'bold');

        $dateWidth = min(300, max(190, $this->textWidth($date, 21, 'bold') + 44));
        $dateX = 1084 - $dateWidth;
        $this->roundedRectangle($dateX, 130, $dateWidth, 46, 23, '#fef3c7');
        $this->writeText($date, $dateX + 22, 160, 21, '#0f172a', 'bold');

        $this->writeLines(
            $this->fitLines($title, 606, 2, 44, 34, 'display'),
            132,
            236,
            '#0f172a',
            'display',
            1.15
        );

        $scriptureLines = $this->fitLines($scripture, 540, 4, 34, 25, 'serif');
        $scriptureY = count($scriptureLines['lines']) > 3 ? 336 : 350;
        $this->writeText('"', 124, $scriptureY - 4, 82, '#93c5fd', 'display');
        $this->writeLines($scriptureLines, 172, $scriptureY, '#0f172a', 'serif', 1.28);

        $referenceY = $scriptureY + (int) round($scriptureLines['size'] * 1.28 * count($scriptureLines['lines'])) + 34;
        $this->writeText($reference, 172, min($referenceY, 506), 23, '#1d4ed8', 'bold');

        $this->roundedRectangle(788, 206, 270, 54, 27, '#eff6ff');
        $this->writeText($appName, 817, 242, 29, '#065f46', 'display');
        $this->writeCenteredFit(mb_strtoupper($growthLabel), 923, 276, 274, 14, 10, '#64748b', 'bold');

        $this->roundedRectangle(758, 308, 326, 132, 28, '#fffbeb');
        $this->writeText(mb_strtoupper($theme), 790, 348, 17, '#92400e', 'bold');
        $this->writeLines(
            $this->fitLines($affirmation, 260, 2, 24, 18, 'serif'),
            790,
            392,
            '#0f172a',
            'serif',
            1.28
        );

        $this->roundedRectangle(758, 460, 326, 44, 22, '#ecfdf5');
        $this->writeText($language, 790, 489, 18, '#047857', 'bold');
        $this->writeRightFit($appHost, 1084, 489, 185, 18, 12, '#334155', 'bold');

        return $this->png();
    }

    private function drawGradient(): void
    {
        $stops = [
            [0, '#ecfdf5'],
            [0.54, '#eff6ff'],
            [1, '#fff7ed'],
        ];

        for ($y = 0; $y < self::HEIGHT; $y++) {
            $position = $y / (self::HEIGHT - 1);
            [$start, $end] = $position <= $stops[1][0]
                ? [$stops[0], $stops[1]]
                : [$stops[1], $stops[2]];

            $range = max(0.001, $end[0] - $start[0]);
            $ratio = ($position - $start[0]) / $range;
            [$r1, $g1, $b1] = $this->rgb($start[1]);
            [$r2, $g2, $b2] = $this->rgb($end[1]);

            imageline(
                $this->image,
                0,
                $y,
                self::WIDTH,
                $y,
                imagecolorallocate(
                    $this->image,
                    (int) round($r1 + ($r2 - $r1) * $ratio),
                    (int) round($g1 + ($g2 - $g1) * $ratio),
                    (int) round($b1 + ($b2 - $b1) * $ratio),
                )
            );
        }
    }

    private function drawColorStrip(): void
    {
        $colors = ['#ef4444', '#f59e0b', '#84cc16', '#10b981', '#06b6d4', '#3b82f6', '#8b5cf6', '#ec4899'];
        $width = (int) ceil(self::WIDTH / count($colors));

        foreach ($colors as $index => $color) {
            imagefilledrectangle($this->image, $index * $width, 0, ($index + 1) * $width, 18, $this->color($color));
        }
    }

    private function roundedRectangle(int $x, int $y, int $width, int $height, int $radius, string $hex, int $alpha = 0): void
    {
        $color = $this->color($hex, $alpha);
        imagefilledrectangle($this->image, $x + $radius, $y, $x + $width - $radius, $y + $height, $color);
        imagefilledrectangle($this->image, $x, $y + $radius, $x + $width, $y + $height - $radius, $color);
        imagefilledellipse($this->image, $x + $radius, $y + $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($this->image, $x + $width - $radius, $y + $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($this->image, $x + $radius, $y + $height - $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($this->image, $x + $width - $radius, $y + $height - $radius, $radius * 2, $radius * 2, $color);
    }

    private function roundedStroke(int $x, int $y, int $width, int $height, int $radius, string $hex, int $thickness): void
    {
        $color = $this->color($hex);
        imagesetthickness($this->image, $thickness);
        imageline($this->image, $x + $radius, $y, $x + $width - $radius, $y, $color);
        imageline($this->image, $x + $radius, $y + $height, $x + $width - $radius, $y + $height, $color);
        imageline($this->image, $x, $y + $radius, $x, $y + $height - $radius, $color);
        imageline($this->image, $x + $width, $y + $radius, $x + $width, $y + $height - $radius, $color);
        imagearc($this->image, $x + $radius, $y + $radius, $radius * 2, $radius * 2, 180, 270, $color);
        imagearc($this->image, $x + $width - $radius, $y + $radius, $radius * 2, $radius * 2, 270, 360, $color);
        imagearc($this->image, $x + $width - $radius, $y + $height - $radius, $radius * 2, $radius * 2, 0, 90, $color);
        imagearc($this->image, $x + $radius, $y + $height - $radius, $radius * 2, $radius * 2, 90, 180, $color);
        imagesetthickness($this->image, 1);
    }

    /**
     * @return array{size:int,lines:array<int, string>}
     */
    private function fitLines(string $text, int $maxWidth, int $maxLines, int $startSize, int $minSize, string $font): array
    {
        for ($size = $startSize; $size >= $minSize; $size -= 2) {
            $lines = $this->wrap($text, $maxWidth, $size, $font);

            if (count($lines) <= $maxLines) {
                return ['size' => $size, 'lines' => $lines];
            }
        }

        $lines = array_slice($this->wrap($text, $maxWidth, $minSize, $font), 0, $maxLines);

        if (count($lines) === $maxLines) {
            $lines[$maxLines - 1] = rtrim((string) preg_replace('/[.,;:!?]+$/', '', $lines[$maxLines - 1])).'...';
        }

        return ['size' => $minSize, 'lines' => $lines];
    }

    /**
     * @return array<int, string>
     */
    private function wrap(string $text, int $maxWidth, int $size, string $font): array
    {
        $words = preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $lines = [];
        $line = '';

        foreach ($words as $word) {
            $candidate = $line === '' ? $word : $line.' '.$word;

            if ($this->textWidth($candidate, $size, $font) <= $maxWidth || $line === '') {
                $line = $candidate;

                continue;
            }

            $lines[] = $line;
            $line = $word;
        }

        if ($line !== '') {
            $lines[] = $line;
        }

        return $lines;
    }

    /**
     * @param  array{size:int,lines:array<int, string>}  $fit
     */
    private function writeLines(array $fit, int $x, int $firstBaseline, string $hex, string $font, float $lineHeight): void
    {
        foreach ($fit['lines'] as $index => $line) {
            $this->writeText($line, $x, $firstBaseline + (int) round($fit['size'] * $lineHeight * $index), $fit['size'], $hex, $font);
        }
    }

    private function writeText(string $text, int $x, int $baseline, int $size, string $hex, string $font): void
    {
        imagettftext($this->image, $size, 0, $x, $baseline, $this->color($hex), $this->fonts[$font], $text);
    }

    private function writeCenteredFit(string $text, int $centerX, int $baseline, int $maxWidth, int $startSize, int $minSize, string $hex, string $font): void
    {
        $size = $startSize;

        while ($size > $minSize && $this->textWidth($text, $size, $font) > $maxWidth) {
            $size--;
        }

        $width = $this->textWidth($text, $size, $font);
        $this->writeText($text, $centerX - (int) floor($width / 2), $baseline, $size, $hex, $font);
    }

    private function writeRightFit(string $text, int $rightX, int $baseline, int $maxWidth, int $startSize, int $minSize, string $hex, string $font): void
    {
        $size = $startSize;

        while ($size > $minSize && $this->textWidth($text, $size, $font) > $maxWidth) {
            $size--;
        }

        $width = $this->textWidth($text, $size, $font);
        $this->writeText($text, $rightX - $width, $baseline, $size, $hex, $font);
    }

    private function textWidth(string $text, int $size, string $font): int
    {
        $box = imagettfbbox($size, 0, $this->fonts[$font], $text);

        if (! $box) {
            return 0;
        }

        return abs($box[2] - $box[0]);
    }

    private function text(mixed $value): string
    {
        $text = html_entity_decode(strip_tags((string) $value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = (string) preg_replace('/\s+/u', ' ', $text);

        return trim($text);
    }

    private function font(string $file): string
    {
        $path = '/usr/share/fonts/truetype/noto/'.$file;

        if (is_file($path)) {
            return $path;
        }

        return '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';
    }

    private function color(string $hex, int $alpha = 0): int
    {
        [$red, $green, $blue] = $this->rgb($hex);

        return imagecolorallocatealpha($this->image, $red, $green, $blue, $alpha);
    }

    /**
     * @return array{0:int,1:int,2:int}
     */
    private function rgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    private function png(): string
    {
        ob_start();
        imagepng($this->image, null, 9);
        $image = (string) ob_get_clean();
        imagedestroy($this->image);

        return $image;
    }
}
