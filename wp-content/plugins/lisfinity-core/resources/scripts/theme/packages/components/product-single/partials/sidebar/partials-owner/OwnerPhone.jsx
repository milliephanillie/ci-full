/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, Fragment } from 'react';
import { map, isEmpty } from 'lodash';
import ReactSVG from 'react-svg';
import cx from 'classnames';
import ViberIcon from '../../../../../../../../images/icons/viber.svg';
import WhatsAppIcon from '../../../../../../../../images/icons/whatsapp.svg';
import SkypeIcon from '../../../../../../../../images/icons/skype.svg';
import TelegramIcon from '../../../../../../../../images/icons/telegram.svg';
import { storeStat } from '../../../../../../vendor/functions';

function OwnerPhone(props) {
  const { phone, product, color, telegram, options } = props;
  const [revealed, setRevealed] = useState(false);
  const phoneHidden = phone['profile-phone'].replace('00', '+').slice(0, -3);
  const phoneClean = phone['profile-phone'].slice(0, 2) === '00' ? phone['profile-phone'].replace(/(\s)|(\D+)/g, '').replace('00', '+') : phone['profile-phone'].replace(/(\s)|(\D+)/g, '');

  const colorClass = cx({
    [color]: color,
    'text-grey-500': !color,
  });
  const phoneColorClass = cx({
    [color]: color,
    'text-grey-1000': !color,
  });

  return (
    <div className="flex flex-wrap justify-between items-start">

      <div className="mb-10">
        {!revealed && <div
          className={`font-bold text-lg bg:text-base lg:text-lg ${phoneColorClass} leading-none`}>{phoneHidden}xxx</div>}
        {!revealed && (options?.['membership-phone'] === 'always' || (options?.['membership-phone'] === 'logged_in' && lc_data.logged_in === '1')) &&
        <button
          type="button"
          onClick={() => {
            setRevealed( true);
            storeStat(product.ID, 2);
          }}
          className={`text-13 ${colorClass}`}
        >
          {lc_data.jst[545]}
        </button>
        }
        {!revealed && ((options?.['membership-phone'] === 'logged_in' && lc_data.logged_in !== '1')) &&
          <span className="relative mt-10 flex items-center btn bg-blue-200 h-40 w-full border border-blue-300 rounded font-normal text-blue-700">{lc_data.jst[740]}</span>
        }
        {revealed && <a href={`tel:${phoneClean}`}
                        className={`flex font-bold text-xl lg:text-lg ${phoneColorClass} leading-none`}>{phone['profile-phone']}</a>}
        {revealed &&
        <button
          type="button"
          onClick={() => {
            setRevealed(false);
          }}
          className={`text-13 ${colorClass}`}
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
    </div>
  );
}

export default OwnerPhone;
