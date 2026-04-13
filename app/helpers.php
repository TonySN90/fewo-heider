<?php

use App\Models\Tenant;

if (! function_exists('current_tenant')) {
    function current_tenant(): ?Tenant
    {
        return app()->bound('currentTenant') ? app('currentTenant') : null;
    }
}

if (! function_exists('ui_labels')) {
    function ui_labels(): array
    {
        $isEn = app()->getLocale() === 'en';

        return $isEn ? [
            'gallery_btn'           => 'View Gallery',
            'contact_btn'           => 'Enquire Now',
            'nav_about'             => 'The Apartment',
            'nav_amenities'         => 'Amenities',
            'nav_gallery'           => 'Gallery',
            'nav_pricing'           => 'Prices',
            'nav_arrival'           => 'Getting Here',
            'nav_contact'           => 'Enquire',
            'nav_contact_footer'    => 'Contact',
            'pricing_eyebrow'       => 'Overview',
            'pricing_title'         => 'Prices & Availability',
            'pricing_table'         => 'Price Table',
            'pricing_plan'          => 'Availability Calendar',
            'col_season'            => 'Season',
            'col_period'            => 'Period',
            'col_night'             => 'per Night',
            'col_minstay'           => 'Min. Stay',
            'legend_free'           => 'Available',
            'legend_booked'         => 'Booked',
            'footer_nav'            => 'Navigation',
            'footer_contact'        => 'Contact',
            'gallery_all'           => 'View all :count photos',
            'impressum'             => 'Imprint',
            'datenschutz'           => 'Privacy Policy',
            'arrival_accommodation' => 'Your Accommodation',
            'arrival_tips'          => 'Travel Tips',
            'scroll_down'           => 'Scroll down',
            'scroll_top'            => 'Scroll to top',
            'a11y_label'            => 'Accessibility',
            'a11y_font_size'        => 'Font size',
            'a11y_appearance'       => 'Appearance',
            'a11y_smaller'          => 'Smaller',
            'a11y_reset'            => 'Reset',
            'a11y_larger'           => 'Larger',
            'a11y_contrast'         => 'Contrast',
            'a11y_spacing'          => 'Spacing',
            'a11y_links'            => 'Links',
            'a11y_motion'           => 'Motion',
        ] : [
            'gallery_btn'           => 'Galerie ansehen',
            'contact_btn'           => 'Jetzt anfragen',
            'nav_about'             => 'Die Wohnung',
            'nav_amenities'         => 'Ausstattung',
            'nav_gallery'           => 'Galerie',
            'nav_pricing'           => 'Preise',
            'nav_arrival'           => 'Anreise',
            'nav_contact'           => 'Anfragen',
            'nav_contact_footer'    => 'Kontakt',
            'pricing_eyebrow'       => 'Übersicht',
            'pricing_title'         => 'Preise & Verfügbarkeit',
            'pricing_table'         => 'Preistabelle',
            'pricing_plan'          => 'Belegungsplan',
            'col_season'            => 'Saison',
            'col_period'            => 'Zeitraum',
            'col_night'             => 'pro Nacht',
            'col_minstay'           => 'Mindestaufenthalt',
            'legend_free'           => 'Frei',
            'legend_booked'         => 'Belegt',
            'footer_nav'            => 'Navigation',
            'footer_contact'        => 'Kontakt',
            'gallery_all'           => 'Alle :count Bilder anschauen',
            'impressum'             => 'Impressum',
            'datenschutz'           => 'Datenschutz',
            'arrival_accommodation' => 'Ihre Unterkunft',
            'arrival_tips'          => 'Anreise-Tipps',
            'scroll_down'           => 'Nach unten scrollen',
            'scroll_top'            => 'Nach oben scrollen',
            'a11y_label'            => 'Barrierefreiheit',
            'a11y_font_size'        => 'Schriftgröße',
            'a11y_appearance'       => 'Darstellung',
            'a11y_smaller'          => 'Kleiner',
            'a11y_reset'            => 'Reset',
            'a11y_larger'           => 'Größer',
            'a11y_contrast'         => 'Kontrast',
            'a11y_spacing'          => 'Abstand',
            'a11y_links'            => 'Links',
            'a11y_motion'           => 'Ruhig',
        ];
    }
}
