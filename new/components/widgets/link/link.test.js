const mocki18next = (lng) => {
    jest.doMock('i18next', () => ({ language: lng }));
    return require('i18next');
}

beforeEach(() => {
    jest.resetModules();
})

test('localizePathname in en', () => {
    mocki18next('en');
    const { localizePathname } = require('./link');
    expect(localizePathname('/talks')).toBe('/new/talks');
    expect(localizePathname('/talks', 'en')).toBe('/new/talks');
    expect(localizePathname('/talks', 'th')).toBe('/new/th/talks');
});

test('localizePathname in th', () => {
    mocki18next('th');
    const { localizePathname } = require('./link');
    expect(localizePathname('/talks')).toBe('/new/th/talks');
    expect(localizePathname('/talks', 'en')).toBe('/new/talks');
    expect(localizePathname('/talks', 'th')).toBe('/new/th/talks');
});

test('localizePathname does not change http', () => {
    const { localizePathname } = require('./link');
    expect(localizePathname('http://www.abhayagiri.org/'))
      .toBe('http://www.abhayagiri.org/');
});

test('localizePathname does not change relative links', () => {
    const { localizePathname } = require('./link');
    expect(localizePathname('foo/bar'))
      .toBe('foo/bar');
});
