/* global lc_data, React */
/**
 * Dependencies.
 */
import {useState, useEffect, useRef} from 'react';
import Slider from 'react-slick';
import {map, uniqBy, isEmpty} from 'lodash';
import ReactPlayer from 'react-player';
import ReactSVG from 'react-svg';
import PlayIcon from '../../../../../images/icons/play.svg';
import ScrollContainer from 'react-indiana-drag-scroll';
import PhotoSwipeGallery from "react-photoswipe";
import {createRef} from "@wordpress/element";

function ProductSliderEl(props) {
  const {product} = props;
  const [currentSlide, setCurrentSlide] = useState(0);
  const [slides, setSlides] = useState(null);
  const [items, setItems] = useState(null);
  const [videos, setVideos] = useState(null);
  const [isOpen, setIsOpen] = useState(false);
  const slider = useRef(null);
  const toScroll = useRef(null);
  const [elSettings, setElSettings] = useState({});
  let wrapper = null;
  let el = createRef();

  useEffect(() => {
    setSlides(uniqBy(product.gallery, 'original'));
    setVideos(product?.videos);
  }, [product]);

  useEffect(() => {
    wrapper = el.current && el.current.closest('.elementor-product-gallery');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);

  useEffect(() => {
    const itemsArr = [];
    map(slides, slide => {
      if (slide) {
        itemsArr.push({
          src: slide.original[0].toString(),
          w: slide.original[1],
          h: slide.original[2],
        });
      }
    });

    setItems(itemsArr);
  }, [slides]);


  const settings = {
    dots: false,
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    afterChange: () => {
      setCurrentSlide(slider.current.innerSlider.state.currentSlide);
    },
  };

  function handleClose() {
    setIsOpen(false);
  }

  const options = {
    index: currentSlide,
    closeOnScroll: false,
  };


  return (
    <div ref={el}>
      {!isEmpty(slides) &&
      <section className="product--slider mb-38">
        <Slider {...settings} ref={slider}>
          {map(slides, (slide, index) => {
            return (
              slide.big &&
              <figure key={index} className="rounded-l overflow-hidden cursor-zoom-in photo-zoom"
              onClick={() => {
                setIsOpen(true);
                setCurrentSlide(index);
              }}>
                <img src={slide.big.toString()} alt="Slide"
                style={{
                  position: 'absolute',
                  top: '0',
                  height: '100%',
                  width: '100%',
                  objectFit: 'cover',
                  maxWidth: 'auto'
                }}
                />
              </figure>
            );
          })}
          {map(videos, (video, index) => {
            return (
              <figure key={index}
                      className={`relative slider--video ${currentSlide === index ? 'slide--current-figure' : 'slide--figure'} w-full rounded-l overflow-hidden photo-zoom`}
                      onClick={() => {
                        setIsOpen(true);
                      }}
              >
                <ReactPlayer
                  url={video.video}
                  height={420}
                  width="100%"
                  controls
                  style={{
                    position: 'relative',
                    zIndex: 99,
                  }}
                  config={{
                    youtube: {
                      playerVars: {rel: 0, modestbranding: 1, showinfo: 0},
                    },
                  }}
                  pip
                />
              </figure>
            );
          })}
        </Slider>
        <div className="slider--thumbnails flex flex-nowrap mt-10 -mx-3 overflow-hidden w-full">
          <ScrollContainer ref={toScroll} className="flex">
            {map(slides, (slide, index) => {
              return (
                slide.thumbnail &&
                <figure
                  key={index}
                  onClick={() => {
                    setCurrentSlide(index);
                    slider.current.slickGoTo(index);
                  }
                  }
                  className={`relative  ${currentSlide === index ? 'slide--current-figure' : 'slide--figure'} transparent rounded cursor-pointer`}>
                  {currentSlide === index &&
                  <span className="slide--current absolute w-full h-full z-10 inset-auto"></span>}
                  <img
                    src={slide.thumbnail.toString()}
                    alt="Slide"
                    className="absolute top-0 left-0 h-full w-full object-cover rounded"
                  />
                </figure>
              );
            })}
            {map(videos, (video, index) => {
              let id = video.video.split('v=')[1];
              const ampersandPosition = id && id.indexOf('&');
              id = id && ampersandPosition !== -1 ? id.substring(0, ampersandPosition) : id;
              return (
                video && <figure
                  key={index}
                  onClick={() => {
                    setCurrentSlide(slides.length + index);
                    slider.current.slickGoTo(slides.length + index);
                  }}
                  className="relative mx-3 cursor-pointer"
                  style={{
                    width: 75,
                    minWidth: 75,
                    height: 75
                  }}
                >
                  {currentSlide === index && <span className="slide--current"></span>}
                  <img
                    src={`https://img.youtube.com/vi/${id}/hqdefault.jpg`}
                    alt="Video"
                    className="top-0 l-0 w-full h-full object-none rounded"
                  />
                  <span className="absolute top-1 left-1 flex-center w-full h-full bg-black z-2 rounded"
                        style={{width: '73px', height: '73px', opacity: '.4'}}>
              <ReactSVG
                src={`${lc_data.dir}dist/${PlayIcon}`}
                className="w-14 h-14 fill-white"
              />
              </span>
                </figure>
              );
            })}
          </ScrollContainer>
        </div>
        {items && <PhotoSwipeGallery isOpen={isOpen} onClose={handleClose} items={items} options={options}/>}
      </section>}
    </div>
  );
}

export default ProductSliderEl;
