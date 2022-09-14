window.addEventListener('load', function() {

    // Add Body Class
    const faketalk_body = document.querySelector("body");
    if(faketalk_body !== undefined && faketalk_body !== null) {
        if(window.location.href.includes("wp-fake-comments-generator")) faketalk_body.classList.add("faketalk");
    }

    // Get Tabbed Menu List
    const faketalk_list = document.querySelectorAll(".faketalk_tabbed_list li");
    if(faketalk_list !== undefined && faketalk_list !==  null) {
        
        // Add onclick event to the list
        faketalk_list.forEach(list => {

            // Anchor must have target to display something
            if(list.hasAttribute("data-tab-target")) {
                list.addEventListener("click", function() {

                    // Remove classes from others > menu
                    faketalk_list.forEach(fallList => {
                        if(fallList.classList.contains("current")) {
                            fallList.classList.remove("current");
                        }
                    })

                    // Remove classes from others > targets
                    let faketalk_windows = document.querySelectorAll(".faketalk_tabbed_window");
                    if(faketalk_windows !== undefined && faketalk_windows !==  null) {
                        faketalk_windows.forEach(fallWindow => {
                        if(fallWindow.classList.contains("show")) {
                            fallWindow.classList.remove("show");
                        }
                    })
                    }

                    // Add current to the menu
                    list.classList.toggle("current");

                    // Hide/show target
                    let target = document.querySelector("#" + list.getAttribute("data-tab-target"));
                    if(target !== undefined && target !== null) {
                        target.classList.toggle("show");
                    }

                });
            }
        });
    }

    // Add "size" to select menu in target selection
    const faketalk_selects = document.querySelectorAll("#faketalk_posts option");
    if(faketalk_selects !== undefined && faketalk_selects !==  null) {

        // Max Size 50
        let faketalk_length = faketalk_selects.length;
        if(faketalk_selects.length > 50) {
            faketalk_length = 50;
        }

        // Add attribute to the <select>
        let faketalkSelect = document.querySelector("#faketalk_posts");
        if(faketalkSelect !== undefined && faketalkSelect !==  null) {
            faketalkSelect.setAttribute("size", faketalk_length) ;
        }
    }

    // Get Date
    const faketalk_date = new Date();

    // Datepicker
    datepicker(
        '#date_from',
        { 
            id: 1, // Instance
            startDay: 1, // Start at Monday
            dateSelected: new Date(faketalk_date.getFullYear()-1, faketalk_date.getMonth(), faketalk_date.getDate()),
            formatter: (input, date, instance) => {
                const value = new Date(date);
                input.value = value.getDate() + "-" + (value.getMonth()+1) + "-" + value.getFullYear() // => "24-12-2022"
            }
        }
    );
    datepicker(
        '#date_to',
        { 
            id: 1,
            startDay: 1, // Start at Monday
            maxDate: new Date(faketalk_date.getFullYear(), faketalk_date.getMonth(), faketalk_date.getDate()), // Max Date Must Be Today
            formatter: (input, date, instance) => {
                const value = new Date(date);
                input.value = value.getDate() + "-" + (value.getMonth()+1) + "-" + value.getFullYear() // => "24-12-2022"
            }
        }
    );
});