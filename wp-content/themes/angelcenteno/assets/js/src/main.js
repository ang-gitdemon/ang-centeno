

import { ParallaxImage } from './modules/ParallaxImage';
import { Barba } from './modules/Barba';

let Main = {
    init: async function () {
        // initialize demo javascript component - async/await invokes some 
        //  level of babel transformation
        const parallaxImage = new ParallaxImage();
        const barba = new Barba();

        // await barba.hooks.after(() => {
            await parallaxImage.init();
        // });
        await barba.init();
    }
}

Main.init();