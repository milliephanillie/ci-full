/* global lc_data, React */

import {useSelector} from "react-redux";

const BusinessAboutEl = () => {
  const data = useSelector(state => state);
  const business = data?.product;
  return (
    business &&
    <section>
      {business?.premium_profile?.about_us &&
      <div
        className="business--about"
        dangerouslySetInnerHTML={{ __html: business?.premium_profile?.about_us }}/>
      }
    </section>
  );
};


export default BusinessAboutEl;
