import register from 'ShopUi/app/registry';
export default register('payone-direct-debit', () => import(/* webpackMode: "lazy" */'./payone-direct-debit'));
