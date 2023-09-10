/* global lc_data, React */
/**
 * Dependencies.
 */
import {useDispatch, useSelector} from 'react-redux';
import ReactSVG from 'react-svg';
import ExpiringAds from '../../widgets/ExpiringAds';
import ExpiringPromotions from '../../widgets/ExpiringPromotions';
import BoltIcon from '../../../../../../images/icons/bolt-alt.svg';
import HammerIcon from '../../../../../../images/icons/construction-hammer.svg';
import PackageIcon from '../../../../../../images/icons/package.svg';
import EnvelopeIcon from '../../../../../../images/icons/envelope.svg';
import Analytics from '../partials/Analytics';

const Overall = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const {business, menuOpen} = data;

  let classes =  'lg:w-1/4';

  if((data?.options?.packages && data?.options?.messenger && !data?.options?.disable_bidding) || (!data?.options?.packages && data?.options?.messenger && data?.options?.disable_bidding) || (data?.options?.packages && !data?.options?.messenger && data?.options?.disable_bidding) ) {
    classes = 'lg:w-1/3';
  } else if ((!data?.options?.packages && data?.options?.messenger && !data?.options?.disable_bidding) || (!data?.options?.packages && !data?.options?.messenger && data?.options?.disable_bidding) || (data?.options?.packages && !data?.options?.messenger && !data?.options?.disable_bidding)) {
    classes = 'lg:w-1/2';
  } else if(data?.options?.packages && data?.options?.messenger && data?.options?.disable_bidding) {
    classes = 'lg:w-1/4'
  } else {
    classes = 'lg:w-full';
  }

  return (
    <div className="flex flex-wrap">

      <section className="dashboard--stats flex flex-wrap justify-center w-full xl:w-11/16">

        <div className="flex flex-wrap -mx-14 w-full">
          {/*<div className={`mt-20 lg:mt-0 px-8 w-full md:w-1/2 ${classes}`}>*/}
          <div className={`mt-20 lg:mt-0 px-8 w-full md:w-full`}>
            <div className="flex flex-col items-center p-40 bg-white rounded shadow-theme">

              <div className="flex-center mx-auto p-4 w-48 h-48 bg-blue-200 rounded-full">
                <ReactSVG
                  src={`${lc_data.dir}dist/${BoltIcon}`}
                  className="w-20 h-20 fill-icon-blue-700"
                />
              </div>

              <span className="mt-20 font-bold text-shadow">{Object.keys(business.ads).length}</span>
              <span className="font-light uppercase">{lc_data.jst[130]}</span>
            </div>
          </div>
          {/*{props.options?.disable_bidding &&*/}
          {/*<div className={`mt-20 lg:mt-0 px-8 w-full md:w-1/2 ${classes}`}>*/}
          {/*  <div className="flex flex-col items-center p-40 bg-white rounded shadow-theme">*/}

          {/*    <div className="flex-center mx-auto p-4 w-48 h-48 bg-green-300 rounded-full">*/}
          {/*      <ReactSVG*/}
          {/*        src={`${lc_data.dir}dist/${HammerIcon}`}*/}
          {/*        className="w-20 h-20 fill-green-700"*/}
          {/*      />*/}
          {/*    </div>*/}

          {/*    <span className="mt-20 font-bold text-shadow">{business.stats.bids}</span>*/}
          {/*    <span className="font-light uppercase">{lc_data.jst[131]}</span>*/}
          {/*  </div>*/}
          {/*</div>*/}
          {/*}*/}

          {/*{props.options?.messenger &&*/}
          {/*<div className={`mt-20 lg:mt-0 px-8 w-full md:w-1/2 ${classes}`}>*/}
          {/*  <div className="flex flex-col items-center p-40 bg-white rounded shadow-theme">*/}

          {/*    <div className="flex-center mx-auto p-4 w-48 h-48 bg-yellow-300 rounded-full">*/}
          {/*      <ReactSVG*/}
          {/*        src={`${lc_data.dir}dist/${EnvelopeIcon}`}*/}
          {/*        className="w-20 h-20 fill-yellow-900"*/}
          {/*      />*/}
          {/*    </div>*/}

          {/*    <span className="mt-20 font-bold text-shadow">{business.stats.messages}</span>*/}
          {/*    <span className="font-light uppercase">{lc_data.jst[132]}</span>*/}
          {/*  </div>*/}
          {/*</div>*/}
          {/*}*/}
          {/*{data?.options?.packages &&*/}
          {/*<div className={`mt-20 lg:mt-0 px-8 w-full md:w-1/2 ${classes}`}>*/}
          {/*  <div className="flex flex-col items-center p-40 bg-white rounded shadow-theme">*/}

          {/*    <div className="flex-center mx-auto p-4 w-48 h-48 bg-orange-200 rounded-full">*/}
          {/*      <ReactSVG*/}
          {/*        src={`${lc_data.dir}dist/${PackageIcon}`}*/}
          {/*        className="w-20 h-20 fill-orange-700"*/}
          {/*      />*/}
          {/*    </div>*/}

          {/*    <span className="mt-20 font-bold text-shadow">{business.stats.packages}</span>*/}
          {/*    <span className="font-light uppercase">{lc_data.jst[133]}</span>*/}
          {/*  </div>*/}
          {/*</div>*/}
          {/*}*/}
        </div>


        <Analytics/>
      </section>

      <aside
        className="dashboard--widgets flex flex-row flex-wrap xl:flex-col justify-between xl:justify-start mt-30 xl:mt-0 ml-auto xl:-mb-20 xl:pl-col w-full xl:w-5/16">
        {!!props.options?.widget_expiring_listings && <ExpiringAds/>}
        {!!props.options?.widget_expiring_promotions && <ExpiringPromotions/>}
      </aside>

    </div>
  );
};

export default Overall;
