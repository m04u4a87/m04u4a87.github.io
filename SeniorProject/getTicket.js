
    //當已經在頂端，回頂端符號隱藏
    const arrow = document.querySelector('.top');  /*透過選擇器(.top)，取得DOM元素，放進變數arrow*/
        window.addEventListener('scroll', () =>     /*針對「視窗」，新增「滾動」這個事件，進行監聽*/
            {
                const scroll = window.scrollY;         /*將滾動視窗y軸這件事，放進變數scroll*/

                if (scroll > 0) {                           /*如果變數scroll(滾動視窗y軸)大於0*/
                    arrow.classList.add('visible');         /*就在變數arrow的class，新增一個叫做visible的class*/   
                } else {                                    /*否則移除叫做visible的class*/
                    arrow.classList.remove('visible');
                }
            }
        );