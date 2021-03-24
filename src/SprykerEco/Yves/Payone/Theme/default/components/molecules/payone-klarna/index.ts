import register from 'ShopUi/app/registry';
export default register('payone-klarna', () => import(
    /* webpackMode: "lazy" */
    /* webpackChunkName: "payone-klarna" */
    './payone-klarna'));
