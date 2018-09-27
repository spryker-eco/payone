import register from 'ShopUi/app/registry';
export default register('payone-credit-card', () => import(/* webpackMode: "lazy" */'./payone-credit-card'));
