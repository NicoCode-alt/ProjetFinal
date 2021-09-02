document.addEventListener("DOMContentLoaded", function(){
    document.querySelectorAll('.sidebar .nav-link').forEach(function(element){
          
        element.addEventListener('click', function (e) {
      
            let nextEl = element.nextElementSibling;
            let parentEl  = element.parentElement;	
      
                if(nextEl) {
                    e.preventDefault();	
                    let mycollapse = new bootstrap.Collapse(nextEl);
                  
                    if(nextEl.classList.contains('show')){
                    mycollapse.hide();
                    }
                    else {
                        mycollapse.show();
    
                        let opened_submenu = parentEl.parentElement.querySelector('.submenu.show');
    
                        if(opened_submenu){
                        new bootstrap.Collapse(opened_submenu);
                        }
                    }
                }
            });
        })
    });

    let slider = document.getElementById("range");
    let output = document.getElementById("demo");
    output.innerHTML = slider.value;

    slider.oninput = function() {
        output.innerHTML = this.value;
    }

    /* const slider =
        document.querySelector("input");

    const value =
        document.querySelector(".value");

            value.textContent = slider.value;
            slider.oninput = function() {
                value.textContent = this.value;
            } */