import React from 'react';
import i18n from 'i18next';
import { useTranslation } from 'react-i18next';
import { useParams, useLocation } from "react-router-dom";

export function getPageNumber(location = null) {
    return parseInt(getQuery(location).p) || 1;
}

export function getQuery(location = null) {
    if (!location) {
        location = window.location;
    }
    const search = location.search;
    let query = {};
    if (typeof(search) === 'string' && search[0] === '?') {
        search.substring(1).split('&').map(function (pair) {
            const n = pair.indexOf('=');
            if (n >= 0) {
                query[pair.substring(0, n)] = pair.substring(n + 1);
            } else {
                query[pair] = null;
            }
        });
    }
    return query;
}

export function lngKey() {
    return i18n.language === 'th' ? 'Th' : 'En';
}

export function pageWrapper(ns, options = {}) {
    return function (component) {
        return function ({lng, ...rest}) {
            const { i18n, t } = useTranslation(ns, options);
            const location = useLocation();
            const props = {
                ...rest,
                i18n, t,
                location,
                query: getQuery(location),
                pageNumber: getPageNumber(location),
                params: useParams(),
                tp: translateProperty,
                thp: translateHtmlProperty
            };
            if (lng) {
                props.lng = lng;
                if (i18n.language !== lng) {
                    console.log('Changing language to ' + lng);
                    i18n.changeLanguage(lng);
                }
            }
            return React.createElement(component, props);
        }
    }
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

export function translateHtmlProperty(obj, name, fallback=true) {
    const key = lngKey();
    let value = obj[name + key];
    if (!value && fallback && key === 'Th') {
        value = obj[name + 'En'];
        return <div title="ตีความจำเป็น" dangerouslySetInnerHTML={{__html: value}} />
    } else {
        return <div dangerouslySetInnerHTML={{__html: value}} />
    }
}

export const tp = translateProperty;
export const thp = translateHtmlProperty;
