import Component from 'ShopUi/models/component';
import ScriptLoader from 'ShopUi/components/molecules/script-loader/script-loader';

declare var Payone;
declare var PayoneGlobals;

export default class CreditCard extends Component {
    form: HTMLFormElement
    paymentForm: HTMLFormElement
    config: any
    hostedIframeConfig: any
    iframes: any
    cardTypeInput: any
    scriptLoader: ScriptLoader
    cardHolderInput: HTMLInputElement
    errorElement: HTMLElement
    isPaymentStatusValid: boolean

    constructor() {
        super();
        this.isPaymentStatusValid = false;
    }

    protected readyCallback(): void {
        this.form = document.querySelector(this.formSelector);
        this.paymentForm = <HTMLFormElement> document.getElementById(this.formId);
        this.cardTypeInput = document.querySelector(this.cardTypeInputSelector);
        this.scriptLoader = <ScriptLoader> document.querySelector('script-loader');
        this.cardHolderInput = <HTMLInputElement> document.getElementById(this.cardHolderId);
        this.errorElement = document.querySelector(this.errorContainer);

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.scriptLoader.addEventListener('loaded', (event: Event) => this.onScriptLoad(event));
        this.cardTypeInput.addEventListener('change', (event: Event) => this.onCardTypeChange(event));
        this.form.addEventListener('submit', (event: Event) => this.onSubmit(event));
    }

    protected onScriptLoad(event: Event): void {
        // Configuration for Hosted Iframe.
        // https://github.com/fjbender/simple-php-integration#build-the-form
        this.config = {
            fields: {
                cardpan: {
                    selector: "cardpan",
                    type: "text",
                },
                cardcvc2: {
                    selector: "cardcvc2",
                    type: "password",
                    size: "4",
                    maxlength: "4",
                },
                cardexpiremonth: {
                    selector: "cardexpiremonth",
                    type: "select",
                    size: "2",
                    maxlength: "2",
                    iframe: {
                        width: "100%",
                    }
                },
                cardexpireyear: {
                    selector: "cardexpireyear",
                    type: "select",
                    iframe: {
                        width: "100%",
                    }
                }
            },
            defaultStyle: {
                input: "font-size: 0.875em; height: 2rem; width: 100%; border-radius: 0; border: 1px solid #dadada;",
                select: "font-size: 0.875em; height: 2rem; width: 100%; border-radius: 0; border: 1px solid #dadada; background-color: #fefefe;",
                iframe: {
                    height: "35px",
                    width: "100%"
                }
            },
            error: "errorOutput",
            language: Payone.ClientApi.Language.de
        };

        this.addCheckCallback();
        this.createPayoneIframe();
    }

    protected addCheckCallback(): void {
        // Payone script works with such global array member.
        // Since our function checkCallback is inside the module, it is absent in "window".
        // Set it explicitly.
        let _self = this;
        window['checkCallback'] = function(response) {
            if (response.status === "VALID") {
                _self.isPaymentStatusValid = true;
                document.getElementById('pseudocardpan').value = response.pseudocardpan;
                document.getElementById('payment-form').submit();
            }
        };
    }

    protected onSubmit(event: Event): void {
        let currentPaymentMethodRadio = <HTMLInputElement> document.querySelector(this.currentPaymentMethodSelector);

        if (currentPaymentMethodRadio.value === 'payoneCreditCard') {
            if (!this.isPaymentStatusValid) {
                event.preventDefault();
                this.checkCreditCard();
            }
        }
    }

    protected checkCreditCard() {
        if (this.iframes.isComplete() && this.cardHolderInput.value) {
            this.iframes.creditCardCheck('checkCallback');
        } else {
            this.errorElement.innerHTML = this.hostedIframeConfig.language.transactionRejected;
        }
    }

    protected createPayoneIframe(): void {
        const input = <HTMLInputElement>this.form.querySelector(this.clientApiConfig);
        const clientApiConfig = JSON.parse(input.value);
        const languageInput = <HTMLInputElement> this.paymentForm.querySelector(this.languageInputSelector);
        const language = languageInput.value.substr(0, 2);

        this.hostedIframeConfig = this.config;

        this.hostedIframeConfig.language = Payone.ClientApi.Language[language];

        this.iframes = new Payone.ClientApi.HostedIFrames(this.hostedIframeConfig, clientApiConfig);

        this.iframes.setCardType(this.cardTypeInput.value);
    }

    protected onCardTypeChange(event: Event): void {
        const inputType = <HTMLInputElement> event.currentTarget;
        this.iframes.setCardType(inputType.value);
    }

    get formSelector(): string {
        return this.getAttribute('form-selector');
    }

    get formId(): string {
        return this.getAttribute('form-id');
    }

    get clientApiConfig(): string {
        return this.getAttribute('client-api-config-selector');
    }

    get languageInputSelector(): string {
        return this.getAttribute('language-input-selector');
    }

    get cardTypeInputSelector(): string {
        return this.getAttribute('card-type-input-selector');
    }

    get scriptId(): string {
        return this.getAttribute('script-id');
    }

    get cardHolderId(): string {
        return this.getAttribute('card-holder-id');
    }

    get errorContainer(): string {
        return this.getAttribute('error-container-selector');
    }

    get currentPaymentMethodSelector(): string {
        return this.getAttribute('current-payment-method-selector');
    }


}