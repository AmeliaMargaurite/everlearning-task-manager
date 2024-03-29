import { closeModal } from "../helpers__1662731975950__.js";

export class ModalOverlay extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		const modalsId = this.getAttribute("modals_id");
		this.className = "overlay";
		this.id = "overlay";
		this.onclick = () => closeModal(modalsId);
		document.body.style.overflow = "hidden";
		window.addEventListener(
			"keydown",
			(e) => {
				if (e.key === "Escape") () => closeModal(modalsId);
			},
			{
				once: true,
			}
		);

		// this.appendChild(modalOverlay);
	}
}
