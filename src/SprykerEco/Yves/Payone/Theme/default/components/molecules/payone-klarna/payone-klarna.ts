/* tslint:disable: max-file-line-count */

import Component from 'ShopUi/models/component';
import ScriptLoader from 'ShopUi/components/molecules/script-loader/script-loader';

declare const Klarna;
const TOKEN_CONTAINER_ID = 'paymentForm_payoneKlarna_payMethodToken';
const IS_VALID_PARAM = 'is_valid';
const GET_TOKEN_URL = '/payone/get-token';
const CONTAINER_ID = '#klarna_container';

interface PaymentData {
    'client_token': string;
    'pay_method': string;
}

interface KlarnaPayMethods {
    KDD: string;
    KIS: string;
    KIV: string;
}

interface AddressData {
    'given_name': string;
    'family_name': string;
    'email': string;
    'street_address': string;
    'postal_code': string;
    'city': string;
    'country': string;
    'phone': string;
}

export default class PayoneKlarna extends Component {
    protected scriptLoader: ScriptLoader;
    protected selectField: HTMLSelectElement;
    protected availablePayment: PaymentData;
    protected availablePaymentArray: PaymentData[] = [];
    protected currentPaymentMethodCategory: string;
    protected currentPaymentCompanyToken: string;
    protected allKlarnaPayMethods: KlarnaPayMethods;
    protected addressData: AddressData = {
        'given_name': this.givenName,
        'family_name': this.familyName,
        'email': this.email,
        'street_address': this.streetAddress,
        'postal_code': this.postalCode,
        'city': this.city,
        'country': this.country,
        'phone': this.phone,
    }

    protected readyCallback(): void {}

    protected init(): void {
        this.scriptLoader = <ScriptLoader>this.getElementsByClassName(`${this.jsName}__script-loader`)[0];
        this.selectField = <HTMLSelectElement>this.getElementsByClassName(`${this.jsName}__select-field`)[0];
        this.allKlarnaPayMethods = <KlarnaPayMethods>JSON.parse(this.klarnaPayMethods());

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapScripLoadEvent();
        this.mapSelectChangeEvent();
    }

    protected mapScripLoadEvent(): void {
        this.scriptLoader.addEventListener('scriptload', () => this.getAvailablePaymentMethods());
    }

    protected mapSelectChangeEvent(): void {
        this.selectField.addEventListener('change', () => this.selectPaymentMethod());
    }

    protected getAvailablePaymentMethods(): void {
        Array.from(this.selectField.options).forEach(option => {
            if (!option.value) {
                return;
            }

            const formData = new FormData();
            formData.append('pay_method', option.value);

            fetch(GET_TOKEN_URL, {method: 'POST', body: formData})
                .then(response => response.json())
                .then(parsedResponse => {
                    if (!parsedResponse[IS_VALID_PARAM]) {
                        return;
                    }

                    this.availablePayment = {
                        'pay_method': option.value,
                        'client_token': parsedResponse.client_token,
                    };

                    this.availablePaymentArray.push(this.availablePayment);
                    option.removeAttribute('disabled');

                })
                .catch((error: Error) => {
                    console.error(error.message);
                });
        })
    }

    protected selectPaymentMethod(): void {
        const paymentMethod = this.availablePaymentArray.find(payment => payment.pay_method === this.selectField.value);
        this.loadKlarna(paymentMethod);
    }

    protected loadKlarna(paymentData: PaymentData): void {
        this.toggleSelectFieldDisable(true);

        Klarna.Payments.init({ client_token: paymentData.client_token });
        Klarna.Payments.load({
            container: CONTAINER_ID,
            payment_method_category: this.allKlarnaPayMethods[paymentData.pay_method],
        },  response => {
            this.toggleSelectFieldDisable(false);
            Klarna.Payments.authorize({
                payment_method_category: this.allKlarnaPayMethods[paymentData.pay_method]
            }, {
                billing_address: this.addressData,
                customer: {
                    date_of_birth: this.dateOfBirth,
                }
            }, response => {
                const tokenContainer = <HTMLInputElement>document.getElementById(TOKEN_CONTAINER_ID);
                tokenContainer.value = response.authorization_token;
            })
        })
    }

    protected toggleSelectFieldDisable(isSelectDisabled: boolean): void {
        this.selectField.disabled = isSelectDisabled;
    }

    protected get givenName(): string {
        return this.getAttribute('given-name');
    }

    protected get familyName(): string {
        return this.getAttribute('family-name');
    }

    protected get email(): string {
        return this.getAttribute('email');
    }

    protected get streetAddress(): string {
        return this.getAttribute('street-address');
    }

    protected get postalCode(): string {
        return this.getAttribute('postal-code');
    }

    protected get city(): string {
        return this.getAttribute('city');
    }

    protected get country(): string {
        return this.getAttribute('country');
    }

    protected get phone(): string {
        return this.getAttribute('phone');
    }

    protected get dateOfBirth(): string {
        return this.getAttribute('date-of-birth');
    }

    protected klarnaPayMethods(): string {
        return this.getAttribute('klarna-pay-methods');
    }
}
