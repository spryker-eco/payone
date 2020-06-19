import Component from 'ShopUi/models/component';

const BANK_ACCOUNT_MODE_BBAN = 'BBAN';

export default class PayoneDirectDebit extends Component {
    form: HTMLFormElement;
    bankAccountModeInputs: HTMLInputElement[];
    bankAccountInput: HTMLInputElement;
    bankCodeInput: HTMLInputElement;
    ibanInput: HTMLInputElement;
    bicInput: HTMLInputElement;

    protected readyCallback(): void {
        this.bankAccountModeInputs = <HTMLInputElement[]>Array.from(
            this.querySelectorAll(this.bankAccountModeSelector)
        );
        this.bankAccountInput = <HTMLInputElement>this.querySelector(this.bankAccountSelector);
        this.bankCodeInput = <HTMLInputElement>this.querySelector(this.bankCodeSelector);
        this.ibanInput = <HTMLInputElement>this.querySelector(this.ibanSelector);
        this.bicInput = <HTMLInputElement>this.querySelector(this.bicSelector);

        this.mapEvents();
        this.toggleInputsStatus();
    }

    protected mapEvents(): void {
        this.bankAccountModeInputs.forEach((input: HTMLInputElement) => {
            input.addEventListener('change', (event: Event) => this.onModeChange(event));
        });
    }

    protected onModeChange(event: Event): void {
        this.toggleInputsStatus();
    }

    protected toggleInputStatus(input: HTMLInputElement, enable: boolean): void {
        if (enable) {
            input.removeAttribute('disabled');

            return;
        }

        input.setAttribute('disabled', 'disabled');
    }

    toggleInputsStatus(): void {
        if (!this.bankAccountMode) {
            return;
        }

        this.toggleInputStatus(this.bankAccountInput, this.isBBANBankAccountMode);
        this.toggleInputStatus(this.bankCodeInput, this.isBBANBankAccountMode);
        this.toggleInputStatus(this.ibanInput, !this.isBBANBankAccountMode);
        this.toggleInputStatus(this.bicInput, !this.isBBANBankAccountMode);
    }

    get isBBANBankAccountMode(): boolean {
        return this.bankAccountMode === BANK_ACCOUNT_MODE_BBAN;
    }

    get bankAccountMode(): string {
        const selectedInput = this.bankAccountModeInputs.find((input: HTMLInputElement) => input.checked);

        return !!selectedInput ? selectedInput.value : '';
    }

    get bankAccountModeSelector(): string {
        return this.getAttribute('bank-account-mode-selector');
    }

    get bankAccountSelector(): string {
        return this.getAttribute('bank-account-selector');
    }

    get bankCodeSelector(): string {
        return this.getAttribute('bank-code-selector');
    }

    get ibanSelector(): string {
        return this.getAttribute('iban-selector');
    }

    get bicSelector(): string {
        return this.getAttribute('bic-selector');
    }
}
