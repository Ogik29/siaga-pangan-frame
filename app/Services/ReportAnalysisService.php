<?php
// Nanti service ini diganti dengan request ke model IndoBERT, 
//karena paper memang menggunakan IndoBERT untuk mengekstrak commodity, location, issue type, dan urgency.

namespace App\Services;

class ReportAnalysisService
{
    public function analyze(string $text): array
    {
        $text = strtolower($text);

        $issueType = 'general';

        if (
            str_contains($text, 'stock') ||
            str_contains($text, 'shortage') ||
            str_contains($text, 'empty')
        ) {
            $issueType = 'scarcity';
        } elseif (
            str_contains($text, 'price') ||
            str_contains($text, 'expensive') ||
            str_contains($text, 'increase')
        ) {
            $issueType = 'price_spike';
        } elseif (
            str_contains($text, 'delay') ||
            str_contains($text, 'late')
        ) {
            $issueType = 'delay';
        } elseif (
            str_contains($text, 'hoarding')
        ) {
            $issueType = 'hoarding';
        }

        $urgency = 'Medium';

        if (
            str_contains($text, 'urgent') ||
            str_contains($text, 'critical') ||
            str_contains($text, 'empty') ||
            str_contains($text, 'shortage')
        ) {
            $urgency = 'High';
        }

        $negativeWords = [
            'shortage',
            'empty',
            'expensive',
            'increase',
            'delay',
            'critical',
            'problem',
        ];

        $negativeCount = 0;

        foreach ($negativeWords as $word) {
            if (str_contains($text, $word)) {
                $negativeCount++;
            }
        }

        $sentiment = max(
            0,
            min(
                1,
                0.5 - ($negativeCount * 0.08)
            )
        );

        return [
            'issue_type' => $issueType,
            'urgency' => $urgency,
            'sentiment_score' => round(
                $sentiment,
                4
            ),
        ];
    }
}
