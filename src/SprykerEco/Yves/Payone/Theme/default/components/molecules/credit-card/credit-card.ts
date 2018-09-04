import Component from 'ShopUi/models/component';
import ScriptLoader from 'ShopUi/components/molecules/script-loader/script-loader';

declare var Payone;

export default class CreditCard extends Component {
    form: HTMLFormElement
    paymentForm: HTMLFormElement
    config: any
    hostedIframeConfig: any
    iframes: any
    cardTypeInput: any
    scriptLoader: ScriptLoader

    constructor() {
        super();
    }

    protected readyCallback(): void {
        this.form = document.querySelector(this.formSelector);
        this.paymentForm = <HTMLFormElement> document.getElementById(this.formId);
        this.cardTypeInput = document.querySelector(this.cardTypeInputSelector);
        this.scriptLoader = <ScriptLoader> document.querySelector('script-loader');

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.scriptLoader.addEventListener('loaded', (event: Event) => this.onLoad(event));

        this.cardTypeInput.addEventListener('change', (event: Event) => this.onCardTypeChange(event));
    }

    protected onLoad(event: Event): void {

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
                        width: "50px",
                    }
                },
                cardexpireyear: {
                    selector: "cardexpireyear",
                    type: "select",
                    iframe: {
                        width: "80px",
                    }
                }
            },
            defaultStyle: {
                input: "font-size: 0.875em; height: 2rem; border-radius: 0; border: 1px solid #dadada;",
                select: "font-size: 0.875em; height: 2rem; border-radius: 0; border: 1px solid #dadada; background-color: #fefefe;",
                iframe: {
                    height: "35px",
                    width: "180px"
                }
            },
            error: "errorOutput",
            language: Payone.ClientApi.Language.de
        };

        this.createIframe();
    }

    protected createIframe(): void {
        const input = <HTMLInputElement>this.form.querySelector(this.clientApiConfig);
        const clientApiConfig = JSON.parse(input.value);
        const languageInput = <HTMLInputElement> this.paymentForm.querySelector(this.languageInputSelector);
        const language = languageInput.value.substr(0, 2);

        this.hostedIframeConfig = this.config;

        this.hostedIframeConfig.language = Payone.ClientApi.Language[language];

        this.iframes = new Payone.ClientApi.HostedIFrames(this.hostedIframeConfig, clientApiConfig);

        //this.onCardTypeChange.call(this.cardTypeInput);
    }

    onCardTypeChange(event: Event): void {
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
}