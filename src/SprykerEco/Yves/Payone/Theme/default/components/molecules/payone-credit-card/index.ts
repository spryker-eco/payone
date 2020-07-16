import register from 'ShopUi/app/registry';
export default register('payone-credit-card', () => import(
    /* webpackMode: "lazy" */
    /* webpackChunkName: "payone-credit-card" */
    './payone-credit-card'));
