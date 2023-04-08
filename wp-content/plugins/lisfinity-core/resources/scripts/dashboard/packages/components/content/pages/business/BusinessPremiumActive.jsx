/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { sprintf, __ } from '@wordpress/i18n';

const BusinessPremiumActive = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen } = data;
  const premium = business.business.premium;
  const product = business.business.premium_product;

  return (
    <section className="premium-profile">

      <div className="dashboard--premium flex flex-wrap p-30 bg-white rounded shadow-theme">
        <div className="w-full">
          <div className="flex flex-wrap items-center justify-between">
            <h3 className="font-bold">{lc_data.jst[146]}</h3>

            <div className="flex flex-wrap -mb-10">
              <div className="flex items-center mt-10 mr-10 py-6 px-12 bg-blue-100 rounded text-grey-900">
                <span className="mr-6">{lc_data.jst[147]}</span>
                <strong>{premium.created_at}</strong>
              </div>

              <div className="flex items-center mt-10 py-6 px-12 bg-red-100 rounded text-grey-900">
                <span className="mr-6">{lc_data.jst[148]}</span>
                <strong>{premium.expires_at}</strong>
              </div>
            </div>

          </div>

          <div className="mt-20">
            <p className="text-grey-700"
               dangerouslySetInnerHTML={{ __html: sprintf(lc_data.jst[149], premium.expires_human) }}/>
            <p className="text-grey-700">{lc_data.jst[150]}</p>
          </div>

        </div>
      </div>
    </section>
  );
};

export default BusinessPremiumActive;
