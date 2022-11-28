import { ParallaxImage } from './modules/ParallaxImage';

let Main = {
    init: async function () {

        // initialize demo javascript component - async/await invokes some 
        //  level of babel transformation
        const parallaxImage = new ParallaxImage();
        await parallaxImage.init();
    
    }
}

Main.init();