<?php

if (!function_exists('htmlExcerpt')) {
    function htmlExcerpt($html, $maxLength = 450) {
        // Öncelikle tüm <script> taglarını temizleyelim:
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        // Düz metni elde etmek için HTML etiketlerini kaldırıyoruz:
        $plainText = strip_tags($html);
        // Eğer metin maxLength'ten kısa ise orijinal HTML'i döndür:
        if (strlen($plainText) <= $maxLength) {
            return $html;
        }
        // Düz metni kısaltıp, sonuna "..." ekleyelim:
        $excerpt = substr($plainText, 0, $maxLength) . '...';
        return $excerpt;
    }
}
