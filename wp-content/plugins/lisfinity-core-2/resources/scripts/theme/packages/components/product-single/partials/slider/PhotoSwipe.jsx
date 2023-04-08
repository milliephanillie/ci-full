/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, useRef } from 'react';
import { map, uniqBy } from 'lodash';
import PhotoSwipeGallery from 'react-photoswipe';

/**
 * Internal dependencies
 */
const PhotoSwipe = (props) => {
  const { product } = props;
  const [isOpen, setIsOpen] = useState(false);
  const [currentSlide, setCurrentSlide] = useState(0);
  const [slides] = useState(uniqBy(product.gallery, 'original'));
  const [videos] = useState(product.videos);

  useEffect(() => {
    const openPhotoSwipe = (event, index) => {
      setCurrentSlide(index - 1);
      setIsOpen(true);
    };

    const zoom = document.querySelectorAll('.photo-zoom');
    if (zoom) {
      zoom.forEach((item, index) => {
        item.addEventListener('click', (event) => openPhotoSwipe(event, index));
      });
    }

    return () => {
      if (zoom) {
        zoom.forEach(item => {
          item.removeEventListener('click', openPhotoSwipe);
        });
      }
    };
  }, []);

  let items = [];
  map(slides, slide => {
    if (slide) {
      items.push({
        src: slide.original[0]?.toString(),
        w: slide.original[1],
        h: slide.original[2],
      });
    }
  });

  const options = {
    index: currentSlide,
    closeOnScroll: false,
  };

  function handleClose() {
    setIsOpen(false);
  }

  return (
    <PhotoSwipeGallery isOpen={isOpen} onClose={handleClose} items={items} options={options}/>
  );
};

export default PhotoSwipe;
