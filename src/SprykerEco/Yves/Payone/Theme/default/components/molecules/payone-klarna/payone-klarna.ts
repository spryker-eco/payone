/* tslint:disable: no-any */
/* tslint:disable: max-file-line-count */
declare var Klarna: any;

import Component from 'ShopUi/models/component';
import ScriptLoader from 'ShopUi/components/molecules/script-loader/script-loader';

const TOKEN_CONTAINER_ID = 'paymentForm_payoneKlarna_payMethodTokens';
const IS_VALID_PARAM = 'is_valid';
const GET_TOKEN_URL = '/payone/get-token';
const CONTAINER_ID = '#klarna_container';

export default class PayoneKlarna extends Component {
    protected scriptLoader: ScriptLoader;
    protected selectField: HTMLSelectElement;
    protected availablePayment = {};
    protected availablePaymentArray = [];
    protected currentPaymentMethodCategory: string;
    protected currentPaymentCompanyToken: string;
    protected allKlarnaPayMethods: [];

    protected readyCallback() {}

    protected init() {
        this.scriptLoader = <ScriptLoader>this.querySelector('script-loader');
        this.selectField = <HTMLSelectElement>this.getElementsByClassName(this.selectFieldClassName)[0];
        this.allKlarnaPayMethods = JSON.parse(this.klarnaPayMethods());

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.scriptLoader.addEventListener('scriptload', (event: Event) => this.onScriptLoad(event));
        this.selectField.addEventListener('change', (event: Event) => this.selectPayMethod(event));
    }

    protected onScriptLoad(event: Event): void {
        this.getAvailableMethods();
    }

    protected getAvailableMethods(): void {
        Array.from(this.selectField.options).map((option) => {
            if (!option.value) {
                return;
            }

            const formData = new FormData();
            formData.append('pay_method', option.value);

            fetch(GET_TOKEN_URL, {method: 'POST', body: formData})
                .then((response) => response.json())
                .then((parsedResponse) => {
                    if (parsedResponse[IS_VALID_PARAM]) {
                        this.availablePayment = {
                            'pay_method': option.value,
                            'client_token': parsedResponse['client_token'],
                        };

                        this.availablePaymentArray.push(this.availablePayment);
                        return;
                    }

                    option.setAttribute('disabled', 'disabled');
                })
                .catch((e: Error) => {
                    console.log(e.message);
                });
        })
    }

    protected selectPayMethod(event: Event): void {
        const currentParams = this.availablePaymentArray.filter(payment => {
            return payment['pay_method'] === this.selectField.value;
        })

        this.loadKlarna(currentParams[0])
    }

    protected loadKlarna(payData: {}): void {
        const component = this;
        this.selectField.setAttribute('disabled', 'disabled');

        Klarna.Payments.init({
            client_token: payData['client_token']
        })

        Klarna.Payments.load({
            container: CONTAINER_ID,
            payment_method_category: component.allKlarnaPayMethods[payData['pay_method']],
        }, function (res) {
            component.selectField.removeAttribute('disabled');

            Klarna.Payments.authorize({
                payment_method_category: component.allKlarnaPayMethods[payData['pay_method']]
            }, {
                billing_address: {
                    given_name: component.givenName,
                    family_name: component.familyName,
                    email: component.email,
                    street_address: component.streetAddress,
                    postal_code: component.postalCode,
                    city: component.city,
                    country: component.country,
                    phone: component.phone,
                },

                customer: {
                    date_of_birth: component.dateOfBirth,
                }
            }, function(res) {

                var tokenContainer = this.getElementById(TOKEN_CONTAINER_ID);
                tokenContainer.value = res.authorization_token;
            })
        })
    }

    protected get selectFieldClassName(): string {
        return this.getAttribute('field-class-name');
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
