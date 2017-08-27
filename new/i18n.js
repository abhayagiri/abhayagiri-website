import React from 'react';
import i18n from 'i18next';

import resBundle from 'i18next-resource-store-loader!./locales/index.js';

i18n.init({
    resources: resBundle,
    fallbackLng: 'en',
    debug: false
});

export function lngKey() {
    return i18n.language === 'th' ? 'Th' : 'En';
}

export function translateProperty(obj, name, fallback=true) {
    const key = lngKey();
    let value = obj[name + key];
    if (!value && fallback && key === 'Th') {
        return <span style={{fontStyle: 'italic'}} title="ตีความจำเป็น">{obj[name + 'En']}</span>;
    } else {
        return value;
    }
}

export const tp = translateProperty;

export default i18n;
