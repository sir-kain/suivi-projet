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

// GET Modal content
const $modalBtns = document.querySelectorAll(
    '[data-path-content]'
);

$modalBtns.forEach(($modalBtn) => {
    $modalBtn.addEventListener("click", async (e) => {
        e.preventDefault()
        const {pathContent, target} = $modalBtn.dataset;
        const $contentTarget = document.querySelector(`#${target} .contentTarget`)
        $contentTarget.innerHTML = '<progress></progress>'
        toggleModal(e)

        const response = await fetch(`${pathContent}?ajax=true`);
        const textContent = await response.text()

        if (!response.ok) {
            $contentTarget.innerHTML = ''
            notyf.error("Une erreur est survenue.");
            return;
        }

        $contentTarget.innerHTML = textContent

    });
});


// POST form
window.handleForm = async function (e) {
    e.preventDefault()
    const form = e.target;
    const action = form.action;
    const response = await fetch(`${action}?ajax=true`, {
        method: "post",
        body: new FormData(form),
    });
    if (response.redirected) {
        window.location.href = response.url;
    } else {
        form.outerHTML = await response.text();
    }
}