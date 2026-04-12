// ============================================================
// COOKIE CONSENT – orestbida/vanilla-cookieconsent
// ============================================================

import * as CookieConsent from 'vanilla-cookieconsent';

export function initCookieConsent(): void {
  CookieConsent.run({
    cookie: {
      name: 'cc_cookie',
      expiresAfterDays: 365,
    },
    guiOptions: {
      consentModal: {
        layout: 'box',
        position: 'bottom right',
      },
      preferencesModal: {
        layout: 'box',
      },
    },
    categories: {
      necessary: {
        enabled: true,
        readOnly: true,
      },
      functional: {
        enabled: false,
        readOnly: false,
      },
    },
    onConsent: () => {
      if (CookieConsent.acceptedCategory('functional')) {
        window.dispatchEvent(new Event('functional-consent-granted'));
      }
    },
    onChange: ({ changedCategories }) => {
      if (changedCategories.includes('functional') && CookieConsent.acceptedCategory('functional')) {
        window.dispatchEvent(new Event('functional-consent-granted'));
      }
    },
    language: {
      default: document.documentElement.lang === 'en' ? 'en' : 'de',
      translations: {
        de: {
          consentModal: {
            title: 'Diese Website verwendet Cookies',
            description:
              'Wir nutzen Cookies für den Betrieb der Website und Kartendienste (Mapbox). Mehr Informationen in unserer <a href="/datenschutz" class="cc-link">Datenschutzerklärung</a>.',
            acceptAllBtn: 'Alle akzeptieren',
            acceptNecessaryBtn: 'Nur notwendige',
            showPreferencesBtn: 'Einstellungen',
          },
          preferencesModal: {
            title: 'Cookie-Einstellungen',
            acceptAllBtn: 'Alle akzeptieren',
            acceptNecessaryBtn: 'Nur notwendige',
            savePreferencesBtn: 'Auswahl speichern',
            closeIconLabel: 'Schließen',
            sections: [
              {
                title: 'Notwendige Cookies',
                description:
                  'Für den ordnungsgemäßen Betrieb der Website erforderlich und können nicht deaktiviert werden.',
                linkedCategory: 'necessary',
              },
              {
                title: 'Funktionale Cookies',
                description:
                  'Ermöglichen die Anzeige interaktiver Karten (Mapbox). Dabei werden Verbindungsdaten an Server in den USA übertragen.',
                linkedCategory: 'functional',
              },
            ],
          },
        },
        en: {
          consentModal: {
            title: 'This website uses cookies',
            description:
              'We use cookies to operate the website and display map services (Mapbox). See our <a href="/datenschutz" class="cc-link">privacy policy</a> for more information.',
            acceptAllBtn: 'Accept all',
            acceptNecessaryBtn: 'Necessary only',
            showPreferencesBtn: 'Preferences',
          },
          preferencesModal: {
            title: 'Cookie preferences',
            acceptAllBtn: 'Accept all',
            acceptNecessaryBtn: 'Necessary only',
            savePreferencesBtn: 'Save preferences',
            closeIconLabel: 'Close',
            sections: [
              {
                title: 'Necessary cookies',
                description:
                  'Required for the proper functioning of the website and cannot be disabled.',
                linkedCategory: 'necessary',
              },
              {
                title: 'Functional cookies',
                description:
                  'Enable interactive maps (Mapbox). Connection data is transferred to servers in the USA.',
                linkedCategory: 'functional',
              },
            ],
          },
        },
      },
    },
  });
}

export function hasFunctionalConsent(): boolean {
  return CookieConsent.acceptedCategory('functional');
}

// Global verfügbar machen für inline onclick-Handler
(window as unknown as Record<string, unknown>)['showCookiePreferences'] = () => {
  CookieConsent.showPreferences();
};
