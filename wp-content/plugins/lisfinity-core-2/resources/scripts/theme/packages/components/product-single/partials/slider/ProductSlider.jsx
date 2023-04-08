/* global lc_data, React */
/**
 * Dependencies.
 */
import {useState, useEffect, useRef} from 'react';
import Slider from 'react-slick';
import {map, uniqBy, isEmpty} from 'lodash';
import ReactPlayer from 'react-player';
import ReactSVG from 'react-svg';
import PlayIcon from '../../../../../../../images/icons/play.svg';
import ScrollContainer from 'react-indiana-drag-scroll';
import PhotoSwipeGallery from 'react-photoswipe';

function ProductSlider(props) {
  const {product} = props;
  const [currentSlide, setCurrentSlide] = useState(0);
  const [slides, setSlides] = useState(null);
  const [items, setItems] = useState(null);
  const [videos] = useState(product.videos);
  const [isOpen, setIsOpen] = useState(false);
  const slider = useRef(null);
  const toScroll = useRef(null);

  useEffect(() => {
    setSlides(uniqBy(product.gallery, 'original'));
    if (product.gallery === undefined) {
      let fallbackImageObject = {
        big: product?.fallback_image,
        original: {
          0: product?.fallback_image,
          1: 965,
          2: 409,
          3: false
        },
        thumbnail: product?.fallback_image
      }
      let slidesArray = [];
      slidesArray.push(fallbackImageObject);
      setSlides(slidesArray);
    }

  }, [product]);

  useEffect(() => {
    const itemsArr = [];
    map(slides, slide => {
      if (slide?.original?.[0]) {
        itemsArr.push({
          src: slide.original[0].toString(),
          w: slide.original[1] || 1920,
          h: slide.original[2] || 1280,
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
    !isEmpty(slides) &&
    <section className="product--slider mb-38">
      <Slider {...settings} ref={slider}>
        {map(slides, (slide, index) => {
          return (
            slide.big &&
            <figure key={index} className="rounded-l overflow-hidden photo-zoom"
                    onClick={() => {
                      setCurrentSlide(index);
                      setIsOpen(true);
                    }}
            >
              {slide?.big && <img src={slide.big.toString()} alt={!isEmpty(slide.alt) ? slide.alt : 'slide'}/>}
            </figure>
          );
        })}
        {map(videos, (video, index) => {
          return (
            <figure key={index} className="relative slider--video w-full rounded-l overflow-hidden photo-zoom"
                    onClick={() => {
                      setCurrentSlide(index);
                      setIsOpen(true);
                    }}
            >
              <ReactPlayer
                url={video.video}
                height={420}
                width="100%"
                controls
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
              slide?.thumbnail &&
              <figure
                key={index}
                onClick={() => {
                  setCurrentSlide(index);
                  slider.current.slickGoTo(index);
                }
                }
                className="relative mx-3 transparent rounded cursor-pointer"
                style={{
                  width: 75,
                  minWidth: 75,
                  height: 75,
                }}
              >
                {currentSlide === index && <span className="slide--current"></span>}
                <img
                  src={slide.thumbnail.toString()}
                  alt={!isEmpty(slide.alt) ? slide.alt : 'slide'}
                  className="absolute top-0 left-0 h-full w-full object-cover rounded"
                  style={{borderRadius: '4px'}}
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
    </section>
  );
}

export default ProductSlider;
