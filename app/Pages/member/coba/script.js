// Page profile
document.addEventListener('alpine:init', () => {
    Alpine.data('member_coba', (token) => ({
        ui: {
            title: "Percobaan",
            loading: false,
            showMoreItems: false,
            modalInstance: null,
            submitCheckout: false,
        },
        model: {
            cart: [],
            subtotal: 0,
            adminFee: 0,
            total: 0,
        },
        data: {
            paymentMethods: [],
            paymentMethodCategories: [],
            methodChosen: null,
            token: token,
        },

        init() {
            document.title = this.title;
            Alpine.store('masagi').currentPage = 'checkout'

            fetchPageData('/checkout/supply/' + this.token)
                .then(response => {
                    if (response.found == 0) {
                        // Remove query string from URL
                        window.history.replaceState({}, document.title, window.location.pathname);
                        return window.PineconeRouter.context.redirect('/iuran')
                    }

                    this.paymentMethods = response.paymentMethods
                    this.paymentMethodCategories = response.paymentMethodCategories

                    this.cart = response.items
                    this.subtotal = response.subtotal
                    this.total = response.subtotal
                })
        },

        convertRupiah(amount) {
            return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },

        setPaymentMethod(method) {
            this.methodChosen = method
            const paymentFeeType = this.paymentMethods[this.methodChosen].paymentFeeType
            this.adminFee = paymentFeeType == 'fixed'
                ? this.paymentMethods[this.methodChosen].paymentFeeForCustomer
                : Math.round(this.paymentMethods[this.methodChosen].paymentFeeForCustomer / 100 * this.subtotal);
            this.total = this.subtotal + this.adminFee

            // Close modal
            const modal = document.getElementById('choosePaymentMethodModal')
            this.modalInstance = window.bootstrap.Modal.getOrCreateInstance(modal)
            this.modalInstance.hide()
        },

        async doCheckout() {
            this.ui.submitCheckout = true
            const confirmedBoolean = await Prompts.confirm('Lanjutkan pembayaran dengan metode ' + this.paymentMethods[this.methodChosen].label + '?')
            if (confirmedBoolean) {
                postPageData("/coba", {method: this.methodChosen, token: this.data.token})
                    .then(response => {
                        window.console.log(response)
                        if (response.found == 1) {
                            window.location.replace(response.result.invoice_url)
                        }
                    })
                    .catch((error) => console.log(error))
            } else {
                this.submitCheckout = false
            }
        }
    }))
})
