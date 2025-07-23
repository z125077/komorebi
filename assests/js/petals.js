window.onload = function () {
    for(let i=0;i<25;i++) {
        let petal = document.createElement('span');
        petal.className = 'petal';
        petal.style.left = Math.random()*100 + 'vw';
        petal.style.animationDuration = 5 + Math.random()*5 + 's';
        petal.style.opacity = 0.5 + Math.random()*0.5;
        document.body.appendChild(petal);
        setTimeout(() => petal.remove(), 15000);
    }
};
