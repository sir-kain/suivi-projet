/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import {Notyf} from "notyf";
import "notyf/notyf.min.css";

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.css";

// start the Stimulus application
import "./bootstrap";

import "./modal";

window.notyf = new Notyf();

document.addEventListener("submit", async (e) => {
    const actions = [...document.querySelectorAll("[name='vente'], [name='depense']")].map($form => $form.action);
    const form = e.target;
    const action = form.action;
    if (actions.includes(action)) {
        e.preventDefault();
        const response = await fetch(action, {
            method: "post",
            body: new FormData(form),
        });
        if (response.redirected) {
            window.location.href = response.url;
        } else {
            form.outerHTML = await response.text();
        }
    }
});

const $modalOpenerBtn = document.querySelectorAll(
    '[data-target="modal-edit-vente"][data-vente-id], [data-target="modal-edit-depense"][data-depense-id]'
);

$modalOpenerBtn.forEach(($modalOpener) => {
    $modalOpener.addEventListener("click", async (e) => {
        const {venteId, depenseId} = $modalOpener.dataset;
        let url = `/vente/${venteId}/edit`
        if (depenseId) {
            url = `/depense/${depenseId}/edit`
        }
        const response = await fetch(url);
        const $form = await response.text();

        if (!response.ok) {
            notyf.error("Une erreur est survenue.");
            return;
        }

        if (depenseId) {
            document
                .querySelectorAll("#modal-edit-depense article form")
                .forEach(($form) => {
                    $form.remove();
                });
            document
                .querySelector("#modal-edit-depense article")
                .insertAdjacentHTML("beforeend", $form);
        } else {
            document
                .querySelectorAll("#modal-edit-vente article form")
                .forEach(($form) => {
                    $form.remove();
                });
            document
                .querySelector("#modal-edit-vente article")
                .insertAdjacentHTML("beforeend", $form);
        }
    });
});

const $modalDetailVenteOpenerBtn = document.querySelectorAll(
    '[data-target="modal-detail-vente"][data-vente-id], [data-target="modal-detail-depense"][data-depense-id]'
);

$modalDetailVenteOpenerBtn.forEach(($modalDetailVenteOpener) => {
    $modalDetailVenteOpener.addEventListener("click", async (e) => {
        const {venteId, depenseId} = $modalDetailVenteOpener.dataset;
        let url = `/vente/${venteId}`
        if (depenseId) {
            url = `/depense/${depenseId}`
        }
        const response = await fetch(url);
        const $form = await response.text();

        if (!response.ok) {
            notyf.error("Une erreur est survenue.");
            return;
        }

        if (depenseId) {
            document
                .querySelectorAll("#modal-detail-depense article table")
                .forEach(($form) => {
                    $form.remove();
                });
            document
                .querySelector("#modal-detail-depense article")
                .insertAdjacentHTML("beforeend", $form);
        } else {
            document
                .querySelectorAll("#modal-detail-vente article table")
                .forEach(($form) => {
                    $form.remove();
                });
            document
                .querySelector("#modal-detail-vente article")
                .insertAdjacentHTML("beforeend", $form);
        }
    });
});


// Forms Handler
const $modalBtns = document.querySelectorAll(
    '[data-form-path]'
);

// GET form
$modalBtns.forEach(($modalBtn) => {
    $modalBtn.addEventListener("click", async (e) => {
        e.preventDefault()
        const {formPath, target} = $modalBtn.dataset;
        const $formParent = document.querySelector(`#${target} .formParent`)
        $formParent.innerHTML = '<progress></progress>'
        toggleModal(e)

        const response = await fetch(formPath);
        const formText = await response.text()

        if (!response.ok) {
            $formParent.innerHTML = ''
            notyf.error("Une erreur est survenue.");
            return;
        }

        $formParent.innerHTML = formText

    });
});


// POST form
window.handleForm = async function (e) {
    e.preventDefault()
    const form = e.target;
    const action = form.action;
    const response = await fetch(action, {
        method: "post",
        body: new FormData(form),
    });
    if (response.redirected) {
        window.location.href = response.url;
    } else {
        form.outerHTML = await response.text();
    }
}