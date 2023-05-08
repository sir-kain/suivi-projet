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