import { gsap } from 'gsap';
import { ScrollTrigger } from "gsap/ScrollTrigger";

export class ParallaxImage {

    
    async init() {
        gsap.registerPlugin(ScrollTrigger);
        let images = document.getElementsByClassName('pimg');
        if(!images.length > 0) return;

        let imagesArr = [].slice.call(images);
        imagesArr.forEach(element => {
            this.renderParallax(element);
        });
    }

    renderParallax = (el) => {
        let direction = el.dataset.direction ?? '-';
        gsap.to(el, {
            scrollTrigger: {
                trigger: el,
                start: 'top',
                // end: 'bottom',
                scrub: 1,
                invalidateOnRefresh: true // to make it responsive
            }, 
            y: (i, target) => direction + 150,
            ease: "none"
        });
    }

}