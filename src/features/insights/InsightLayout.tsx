import React from "react";

function InsightLayout({ children }: { children: React.ReactNode }) {
  return (
    <div className="w-full sm:w-[95%] max-w-[1250px] mx-auto py-[5rem] px-2">
      {children}
    </div>
  );
}

export default InsightLayout;
