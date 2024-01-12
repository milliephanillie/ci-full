import ReactSVG from "react-svg";
import CalculatorIcon from "../../../../../../../images/icons/calculator.svg";
import {sprintf} from "@wordpress/i18n";
import SafetyTips from "./SafetyTips";

function FinanceCalculator(props) {
    const { product, currentUser } = props;

    return (
        <div className="tips flex flex-wrap items-center">
            <div className="tips--icon pr-20">
                <div className="flex-center bg:mb-10 xl:mb-0 p-10 bg-blue-200 rounded-full" style={{ width: '70px', height: '70px' }}>
                    <ReactSVG
                        src={`${lc_data.dir}dist/${CalculatorIcon}`}
                        className="w-36 h-36 fill-icon-tips"
                    />
                </div>
            </div>

            <div className="tips--content flex flex-col w-2/3 bg:w-full xl:w-2/3">
                <h6 className="widget--label mb-5 font-bold">Gearhead Finance</h6>
                <p>Flexible Financing for all types of equipment.</p>
                <a
                    href={'https://gearhead-finance.com'}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-white finance-apply-now-button"
                >
                    Apply Now
                </a>
            </div>
        </div>
    )
}

export default FinanceCalculator;