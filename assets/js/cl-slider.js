document.addEventListener('DOMContentLoaded', () => {
    if (document.body.classList.contains('block-editor-page')) return;
    if (!document.querySelector('.cl-slider')) return;

    document.querySelectorAll('.cl-slider').forEach((block) => {
        const list = block.querySelector('.wp-block-post-template');
        if (!list) return;

        let swiperRoot = block.querySelector('.swiper');
        if (!swiperRoot) {
            swiperRoot = document.createElement('div');
            swiperRoot.className = 'swiper';

            list.parentNode.insertBefore(swiperRoot, list);
            swiperRoot.appendChild(list);
        }

        list.classList.add('swiper-wrapper');

        list.querySelectorAll(':scope > li').forEach((item) => {
            item.classList.add('swiper-slide');
        });

        let prev = block.querySelector('.swiper-button-prev');
        let next = block.querySelector('.swiper-button-next');
        let pagination = block.querySelector('.swiper-pagination');

        if (!prev) {
            prev = document.createElement('div');
            prev.className = 'swiper-button-prev';
            swiperRoot.appendChild(prev);
        }

        if (!next) {
            next = document.createElement('div');
            next.className = 'swiper-button-next';
            swiperRoot.appendChild(next);
        }

        if (!pagination) {
            pagination = document.createElement('div');
            pagination.className = 'swiper-pagination';
            swiperRoot.appendChild(pagination);
        }

        if (swiperRoot.swiper) {
            swiperRoot.swiper.destroy(true, true);
        }

        new Swiper(swiperRoot, {
            slidesPerView: 1.15,
            spaceBetween: 18,
            speed: 600,
            grabCursor: true,
            watchOverflow: true,
            breakpoints: {
            0: {
                slidesPerView: 1,
                spaceBetween: 16
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 28
            }
            },
            navigation: {
                prevEl: prev,
                nextEl: next
            },
            pagination: {
                el: pagination,
                clickable: true
            }
        });
    });
});
