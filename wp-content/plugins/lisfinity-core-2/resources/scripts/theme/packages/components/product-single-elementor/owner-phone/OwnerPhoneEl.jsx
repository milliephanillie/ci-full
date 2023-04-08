/* global lc_data, React */
/**
 * External dependencies.
 */
import {useState, Fragment} from 'react';
import {map, isEmpty} from 'lodash';
import ReactSVG from 'react-svg';
import cx from 'classnames';
import ViberIcon from '../../../../../../images/icons/viber.svg';
import WhatsAppIcon from '../../../../../../images/icons/whatsapp.svg';
import SkypeIcon from '../../../../../../images/icons/skype.svg';
import TelegramIcon from '../../../../../../images/icons/telegram.svg';
import {storeStat} from '../../../../vendor/functions';
import {createRef, useEffect} from "@wordpress/element";

function OwnerPhoneEl(props) {
  const {phone, product, color, telegram} = props;
  const [revealed, setRevealed] = useState(false);
  const phoneHidden = phone['profile-phone'].slice(0, -3);
  const phoneClean = phone['profile-phone'].replace('+', '00').replace(/(\s)|(\D+)/g, '');
  const [elSettings, setElSettings] = useState({});
  let wrapper = null;

  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-product-owner-phone');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);

  const colorClass = cx({
    [color]: color,
    'text-grey-500': !color,
  });
  const phoneColorClass = cx({
    [color]: color,
    'text-grey-1000': !color,
  });


  return (
    <div className="flex flex-wrap justify-between items-start" ref={el}>
      {(elSettings['membership_phone'] === 'always' || (elSettings['membership_phone'] === 'logged_in' && lc_data.logged_in === '1')) &&
      <Fragment>
        <div className="mb-10">
          {!revealed && <div className="flex">
            <div
              className={`font-bold text-lg bg:text-base lg:text-lg flex items-center revealed-phone ${phoneColorClass} leading-none`}>{phoneHidden}</div>
            <span className="font-bold text-lg bg:text-base lg:text-lg hidden-phone">xxx</span></div>}
          {!revealed &&
          <button
            type="button"
            onClick={() => {
              setRevealed(true);
              storeStat(product.ID, 2);
            }}
            className={`text-13 owner-phone-button ${colorClass}`}
          >
            {lc_data.jst[545]}
          </button>
          }
          {revealed && <a href={`tel:${phoneClean}`}
                          className={`flex font-bold text-xl lg:text-lg revealed-phone ${phoneColorClass} leading-none`}><span
            className="mb-4 mt-4">{phone['profile-phone']}</span></a>}
          {revealed &&
          <button
            type="button"
            onClick={() => {
              setRevealed(false);
            }}
            className={`text-13 owner-phone-button ${colorClass}`}
          >
            {lc_data.jst[597]}
          </button>
          }
        </div>

        {!isEmpty(phone['profile-phone-apps']) &&
        <div className=" flex relative top-2 ml-10">{map(phone['profile-phone-apps'], (app, index) => {
          return (
            <Fragment key={index}>
              {app === 'viber' && <a href={`viber://chat/?number=${phoneClean}`}>
                <ReactSVG
                  src={`${lc_data.dir}dist/${ViberIcon}`}
                  className={`w-14 h-14 ${props.type === 'business' ? 'fill-white' : 'fill-viber'}`}
                />
              </a>
              }
              {app === 'whatsapp' && <a href={`https://api.whatsapp.com/send?phone=${phoneClean}`} className=" ml-12">
                <ReactSVG
                  src={`${lc_data.dir}dist/${WhatsAppIcon}`}
                  className={`w-14 h-14 ${props.type === 'business' ? 'fill-white' : 'fill-whatsapp'}`}
                />
              </a>
              }
              {app === 'skype' && <a href={`callto://${phoneClean}?`} className=" ml-10">
                <ReactSVG
                  src={`${lc_data.dir}dist/${SkypeIcon}`}
                  className={`w-14 h-14 ${props.type === 'business' ? 'skype-white fill-white' : 'fill-skype'}`}
                />
              </a>
              }
            </Fragment>
          );
        })}
          {telegram && !isEmpty(telegram) &&
          <a href={`https://telegram.me/${telegram}?`} className=" ml-10">
            <ReactSVG
              src={`${lc_data.dir}dist/${TelegramIcon}`}
              className={`relative w-20 h-20 ${props.type === 'business' ? 'fill-white' : ''}`}
              style={{
                top: -1,
              }}
            />
          </a>
          }
        </div>}
      </Fragment>}
    </div>
  );
}

export default OwnerPhoneEl;
