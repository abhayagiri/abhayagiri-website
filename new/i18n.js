import i18n from 'i18next';

import resBundle from 'i18next-resource-store-loader!./locales/index.js';

i18n.init({
    resources: resBundle,
    fallbackLng: 'en',
    debug: false
});

export default i18n;
