import Description from "./Description";
import HouseImages from "./HouseImages";
import InsightLayout from "./InsightLayout";
import InsightsCarousel from "./InsightsCarousel";
import Title from "./Title";

function Insights() {
  return (
    <section className="mx-auto bg-red-50">
      <InsightLayout>
        <Title />
        <HouseImages />
        <Description />
        <InsightsCarousel />
      </InsightLayout>
    </section>
  );
}

export default Insights;
