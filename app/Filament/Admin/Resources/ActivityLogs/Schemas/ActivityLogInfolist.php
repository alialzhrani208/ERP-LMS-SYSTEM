<?php

namespace App\Filament\Admin\Resources\ActivityLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Spatie\Activitylog\Models\Activity;

class ActivityLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('causer.name')
                    ->label('المستخدم المسؤول')
                    ->default('نظام / زائر'),

                TextEntry::make('description')
                    ->label('نوع العملية')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                         default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => 'إضافة',
                        'updated' => 'تعديل',
                        'deleted' => 'حذف',
                        default => $state,
                    }),

                TextEntry::make('subject_type')
                    ->label('النموذج المستهدف')
                    ->formatStateUsing(fn ($state) => $state ? class_basename($state) : 'لا يوجد'),

                TextEntry::make('subject_id')
                    ->label('الحقل المستهدف (اسم السجل)')
                    ->state(function (Activity $record) {
                        $state = $record->subject_type;
                        if (! $state) {
                            return 'لا يوجد';
                        }

                        $subject = $record->subject;

                        if ($subject) {
                            return $subject->name
                                ?? $subject->title
                                ?? $subject->department?->name
                                ?? (class_basename($state).' #'.$subject->id);
                        }

                        $properties = $record->properties;
                        if (! empty($properties['old']['name'])) {
                            return $properties['old']['name'];
                        }
                        if (! empty($properties['old']['title'])) {
                            return $properties['old']['title'];
                        }
                        if (! empty($properties['attributes']['name'])) {
                            return $properties['attributes']['name'];
                        }
                        if (! empty($properties['attributes']['title'])) {
                            return $properties['attributes']['title'];
                        }

                        return class_basename($state).' #'.($record->subject_id ?? 'محذوف');
                    }),

                TextEntry::make('created_at')
                    ->label('وقت الحدث')
                    ->dateTime('Y-m-d H:i:s'),

                // عرض القيم السابقة بذكاء
                TextEntry::make('properties.old')
                    ->label('القيم السابقة (قبل التعديل)')
                    ->state(function (Activity $record) {
                        $oldData = $record->properties['old'] ?? [];
                        if (empty($oldData)) {
                            return null;
                        }

                        $output = [];
                        foreach ($oldData as $key => $value) {
                            $valStr = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : (string) $value;
                            $output[] = "• {$key}: {$valStr}";
                        }

                        return implode("\n", $output);
                    })
                    ->visible(fn (Activity $record): bool => ! empty($record->properties['old'])),

                TextEntry::make('properties.attributes')
                    ->label('القيم الجديدة')
                    ->state(function (Activity $record) {
                        $attrs = $record->properties['attributes'] ?? ($record->properties ?: []);
                        unset($attrs['old']);

                        if (empty($attrs)) {
                            return null;
                        }

                        $output = [];
                        foreach ($attrs as $key => $value) {
                            $valStr = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : (string) $value;
                            $output[] = "• {$key}: {$valStr}";
                        }

                        return implode("\n", $output);
                    })
                    ->visible(fn (Activity $record): bool => ! empty($record->properties['attributes']) || ! empty($record->properties)),
            ]);
    }
}
