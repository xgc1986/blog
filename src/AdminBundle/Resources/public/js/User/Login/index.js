'use strict';

(function() {
  class Login {

    constructor() {
      this.form = _id('login-form');

      this.login = this.login.bind(this);
    }

    clearErrors() {
      let errors = this.form._class('has-error');
      for (let error of errors) {
        error.classList.remove("has-error");
      }
    }

    login(event) {

      event.preventDefault();

      this.clearErrors();

      event.target._submit(event,
          (data, http) => {
            location.href = this.form._tag('form')[0].dataset.target;
          },

          (error) => {
            let message = "";
            if (error.status === 500) {
              message = "El servidor no se encuentra disponible";
            } else {
              message = "Datos incorrectos";
            }
            this.showError(message, error.status);
          }
      )
    }

    showError(message, status) {
      if (status === 500) {
        this.form._tag('form')[0].classList.add('has-error');
      } else {
        this.form._tag('form')[0].classList.add('has-error');
      }

    }
  }

  window.login = new Login();
})();
